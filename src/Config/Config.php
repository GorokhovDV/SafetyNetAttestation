<?php
namespace SafetyNet\Config;

use SafetyNet\Config\Exception\WrongVerifierType;
use SafetyNet\Verifier\VerifierType;

class Config
{
    const VERIFIER_TYPE = 'verifierType';
    const VERIFIER_TIMESTAMP_DIFF = 'timestampDiffMS';
    const VERIFIER_CERTIFICATE_DIGEST_SHA256 = 'apkCertificateDigestSha256';
    const VERIFIER_PACKAGE_NAME = 'apkPackageName';
    const VERIFIER_API_KEY = 'apiKey';
    const VERIFIER_HARDWARE_BACKED = 'hardwareBacked';

    private VerifierType $verifierType;
    private int $timestampDiffMS = 10 * 60 * 60 * 1000;
    private array $apkCertificateDigestSha256 = [];
    private array $apkPackageName = [];
    private string $apiKey;
    private bool $hardwareBacked = false;

    public function __construct(array $configOptions)
    {
        self::validateOptions($configOptions);

        foreach ($configOptions as $optionKey => $optionValue) {
            if (property_exists($this, $optionKey)) {
                $this->{$optionKey} = $optionValue;
            }
        }
    }

    private static function validateOptions($configOptions): void
    {
        if (
            !array_key_exists(self::VERIFIER_TYPE, $configOptions)
            || !($configOptions[self::VERIFIER_TYPE] instanceof  VerifierType)
        ) {
            throw new WrongVerifierType();
        }
    }

    public function getVerifierType(): VerifierType
    {
        return $this->verifierType;
    }

    public function getTimeStampDiffInterval(): int
    {
        return $this->timestampDiffMS;
    }

    public function getApkCertificateDigestSha256(): array
    {
        return $this->apkCertificateDigestSha256;
    }

    public function getApkPackageName(): array
    {
        return $this->apkPackageName;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getHardwareBacked(): bool
    {
        return $this->hardwareBacked;
    }


}