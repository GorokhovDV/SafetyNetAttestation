<?php

namespace SafetyNet\Verifier;

use SafetyNet\Config\Config;
use SafetyNet\Nonce;
use SafetyNet\Statement\Statement;
use SafetyNet\Statement\StatementBody;
use SafetyNet\Statement\StatementHeader;
use SafetyNet\Verifier\Exception\ApkDigestShaError;
use SafetyNet\Verifier\Exception\ApkNameError;
use SafetyNet\Verifier\Exception\BasicIntegrityFieldError;
use SafetyNet\Verifier\Exception\CertificateChainError;
use SafetyNet\Verifier\Exception\CertificateHostnameError;
use SafetyNet\Verifier\Exception\ProfileMatchFieldError;
use SafetyNet\Verifier\Exception\TimestampFieldError;
use SafetyNet\Verifier\Exception\WrongNonce;

abstract class Verifier
{
    private const ISSUING_HOSTNAME = 'attest.android.com';

    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function verify(Nonce $nonce, Statement $statement): bool
    {
        return $this->guardSignature($statement)
            && $this->guardHeaders($statement)
            && $this->guardBody($nonce, $statement);
    }

    abstract protected function guardSignature(Statement $statement): bool;

    private function guardHeaders(Statement $statement) : bool
    {
        $headers = $statement->getHeader();
        return $this->guardAttestHostname($headers) && $this->guardCertificateChain($headers);
    }

    private function guardAttestHostname(StatementHeader $header): bool
    {
        $commonNames = $header->getCertificateChain()->getDNProp('CN');
        $issuingHostname = $commonNames[0] ?? null;

        if ($issuingHostname !== self::ISSUING_HOSTNAME) {
            throw new CertificateHostnameError(
                'Certificate isn\'t issued for the hostname ' . self::ISSUING_HOSTNAME
            );
        }

        return true;
    }

    private function guardCertificateChain(StatementHeader $header): bool
    {
        if (!$header->getCertificateChain()->validateSignature()) {
            throw new CertificateChainError('Certificate chain signature is not valid');
        }

        return true;
    }

    private function guardBody(Nonce $nonce, Statement $statement) : bool
    {
        $body = $statement->getBody();
        return $this->guardNonce($nonce, $body)
            && $this->guardDeviceIsNotRooted($body)
            && $this->guardTimestamp($body)
            && $this->guardApkCertificateDigestSha256($body)
            && $this->guardApkPackageName($body)
            && $this->guardHardwareBacked($body);
    }

    private function guardNonce(Nonce $nonce, StatementBody $statementBody): bool
    {
        $statementNonce = $statementBody->getNonce();

        if (!$statementNonce->isEqual($nonce)) {
            throw new WrongNonce('Invalid nonce');
        }

        return true;
    }

    private function guardDeviceIsNotRooted(StatementBody $statementBody): bool
    {
        $ctsProfileMatch = $statementBody->getCtsProfileMatch();
        $basicIntegrity = $statementBody->getBasicIntegrity();

        if (empty($ctsProfileMatch) || !$ctsProfileMatch) {
            throw new ProfileMatchFieldError('Device is rooted');
        }

        if (empty($basicIntegrity) || !$basicIntegrity) {
            throw new BasicIntegrityFieldError('Device can be rooted');
        }

        return true;
    }

    private function guardTimestamp(StatementBody $statementBody): bool
    {
        $timestampDiff = $this->config->getTimeStampDiffInterval();
        $timestampMs = $statementBody->getTimestampMs();

        if (abs(microtime(true) * 1000 - $timestampMs) > $timestampDiff) {
            throw new TimestampFieldError('TimestampMS and the current time is more than ' . $timestampDiff . ' MS');
        }

        return true;
    }

    private function guardApkCertificateDigestSha256(StatementBody $statementBody): bool
    {
        $apkCertificateDigestSha256 = $this->config->getApkCertificateDigestSha256();
        $testApkCertificateDigestSha256 = $statementBody->getApkCertificateDigestSha256();

        if (empty($testApkCertificateDigestSha256)) {
            throw new ApkDigestShaError('Empty apkCertificateDigestSha256 field');
        }

        $configSha256 = [];
        foreach ($apkCertificateDigestSha256 as $sha256) {
            $configSha256[] = base64_encode(hex2bin($sha256));
        }

        foreach ($testApkCertificateDigestSha256 as $digestSha) {
            if (in_array($digestSha, $configSha256)) {
                return true;
            }
        }

        throw new ApkDigestShaError('apkCertificateDigestSha256 is not valid');
    }

    private function guardApkPackageName(StatementBody $statementBody): bool
    {
        $apkPackageName = $this->config->getApkPackageName();
        $testApkPackageName = $statementBody->getApkPackageName();

        if (empty($testApkPackageName)) {
            throw new ApkNameError('Empty apkPackageName field');
        }

        if (!in_array($testApkPackageName, $apkPackageName)) {
            throw new ApkNameError('apkPackageName ' . $testApkPackageName. ' not equal ' . join(", ", $apkPackageName));
        }

        return true;
    }

    private function guardHardwareBacked(StatementBody $statementBody): bool
    {
        return !$this->config->getHardwareBacked()
            || in_array('HARDWARE_BACKED',explode(',',$statementBody->getEvaluationType()),true)!==false;
    }
}