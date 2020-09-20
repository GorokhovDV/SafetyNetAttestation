<?php

namespace SafetyNet\Statement;

use phpseclib\File\X509;
use SafetyNet\RootGoogleCertService;
use SafetyNet\Statement\Exception\CertificateCALoadError;
use SafetyNet\Statement\Exception\CertificateLoadError;
use SafetyNet\Statement\Exception\EmptyAlgorithmField;
use SafetyNet\Statement\Exception\MissingCertificates;
use SafetyNet\Statement\Exception\RootCertificateError;

class StatementHeader
{
    private string $algorithm;
    private X509 $certificateChain;

    public function __construct(array $headers)
    {
        $this->algorithm = $this->extractAlgorithm($headers);
        $this->certificateChain = $this->extractCertificateChain($headers);
    }

    public function getCertificateChain(): X509
    {
        return $this->certificateChain;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    private function extractAlgorithm(array $headers): string
    {
        if (empty($headers['alg'])) {
            throw new EmptyAlgorithmField('Empty alg field in headers');
        }

        return $headers['alg'];
    }

    private function extractCertificateChain(array $headers): X509
    {
        if (empty($headers['x5c'])) {
            throw new MissingCertificates('Missing certificates');
        }

        $x509 = new X509();
        if ($x509->loadX509(array_shift($headers['x5c'])) === false) {
            throw new CertificateLoadError('Failed to load certificate');
        }

        while ($textCertificate = array_shift($headers['x5c'])) {
            if ($x509->loadCA($textCertificate) === false) {
                throw new CertificateCALoadError('Failed to load certificate');
            }
        }

        if ($x509->loadCA(RootGoogleCertService::rootCertificate()) === false) {
            throw new RootCertificateError('Failed to load Root-CA certificate');
        }

        return $x509;
    }
}