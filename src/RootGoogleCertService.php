<?php

namespace SafetyNet;

use phpseclib\File\X509;
use SafetyNet\Statement\Exception\RootCertificateError;

class RootGoogleCertService
{
    const SAVE_CACHE_FILE_NAME = 'GlobalSign.pem';
    const CRT_FILE_URL = 'https://pki.goog/gsr2/GSR2.crt';

    public static function rootCertificate(): string
    {
        $certificate = self::findInLocalCache();

        if (!self::validateCertFile($certificate)) {
            $certificate = null;
        }

        if (!empty($certificate)) {
            return $certificate;
        }

        $certificate = self::findInLocalBundle();

        if (!empty($certificate)) {
            return $certificate;
        }

        return $certificate = self::getCertificateFromGoogle();
    }

    private static function findInLocalBundle(): ?string
    {
        $localCerts = openssl_get_cert_locations();

        if (empty($localCerts['ini_cafile'])
            || !($caCerts = file_get_contents($localCerts['ini_cafile']))
        ) {
            throw new RootCertificateError('Local certificate bundle is unavailable');
        }

        $rawCerts = explode("-----END CERTIFICATE-----", str_replace("-----BEGIN CERTIFICATE-----","", $caCerts));
        foreach ($rawCerts as $rawCert) {
            $rawCert = trim($rawCert);
            if (empty($rawCert)) {
                continue;
            }
            $x509 = new X509();
            $x509->loadX509($rawCert);
            $CN = $x509->getDNProp('CN');
            if (!empty($CN) && $CN[0] == 'GlobalSign') {
                self::saveToLocalCache($rawCert);
                return $rawCert;
            }
        }

        return null;
    }

    private static function saveToLocalCache(string $rawCert): void
    {
        self::checkTMPDir();
        @file_put_contents(self::getCertCacheFile(), $rawCert);
    }

    private static function checkTMPDir(): bool
    {
        $tmpDir = self::getTMPDir();
        if (is_dir($tmpDir)) {
            return true;
        }

        return mkdir($tmpDir);
    }

    private static function getTMPDir(): string
    {
        return __DIR__ . "/../tmp";
    }

    private static function getCertCacheFile(): string
    {
        return self::getTMPDir() . '/' . self::SAVE_CACHE_FILE_NAME;
    }

    private static function findInLocalCache(): ?string
    {
        if (!is_file(self::getCertCacheFile())) {
            return null;
        }

        return @file_get_contents(self::getCertCacheFile());
    }

    private static function getCertificateFromGoogle(): ?string
    {
        $crtFile = @file_get_contents(self::CRT_FILE_URL);
        if (empty($crtFile)) {
            throw new RootCertificateError("Can't load root cert from google");
        }
        $crtFileContent = chunk_split(base64_encode($crtFile), 64, PHP_EOL);

        self::saveToLocalCache($crtFileContent);
        return $crtFileContent;
    }

    private static function validateCertFile(?string $certificate): bool
    {
        if (empty($certificate)) {
            return false;
        }

        $cert = new X509();
        $cert->loadX509($certificate);
        return $cert->validateDate();
    }
}