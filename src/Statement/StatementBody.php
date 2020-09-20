<?php

namespace SafetyNet\Statement;

use SafetyNet\Nonce;

class StatementBody
{
    private Nonce $nonce;
    private int $timestampMs;
    private string $apkPackageName;
    private string $apkDigestSha256;
    private bool $ctsProfileMatch;
    private array $apkCertificateDigestSha256;
    private bool $basicIntegrity;

    public function __construct(array $body)
    {
        foreach ($body as $bodyKey => $bodyValue) {
            if (!property_exists($this, $bodyKey)) {
                continue;
            }
            switch ($bodyKey) {
                case 'nonce' : {
                    $this->{$bodyKey} = new Nonce($bodyValue);
                    break;
                }
                default : {
                    $this->{$bodyKey} = $bodyValue;
                    break;
                }
            }
        }
    }

    public function getNonce(): Nonce
    {
        return $this->nonce;
    }

    public function getCtsProfileMatch()
    {
        return $this->ctsProfileMatch;
    }

    public function getBasicIntegrity()
    {
        return $this->basicIntegrity;
    }

    public function getTimestampMs(): int
    {
        return $this->timestampMs;
    }

    public function getApkPackageName(): string
    {
        return $this->apkPackageName;
    }

    public function getApkDigestSha256(): string
    {
        return $this->apkDigestSha256;
    }

    public function getApkCertificateDigestSha256(): array
    {
        return $this->apkCertificateDigestSha256;
    }
}