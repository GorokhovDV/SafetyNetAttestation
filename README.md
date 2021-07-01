SafetyNet offline/online attestation library
===============================
* **PHP version:** 7.4+
* **Packagist:** [gorokhovdv/safetynet-verification](https://packagist.org/packages/gorokhovdv/safetynet-verification) [![Total Downloads](https://poser.pugx.org/gorokhovdv/safetynet-verification/downloads.png)](https://packagist.org/packages/gorokhovdv/safetynet-verification)
* **Composer:** `composer require gorokhovdv/safetynet-verification`

***

## Quick Start

### Install

If you use composer in your project then you can install SafetyNetAttestation as package.

`composer require gorokhovdv/safetynet-verification`

### Example for online verification

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use \SafetyNet\Config\Config;
use \SafetyNet\Statement\Statement;
use \SafetyNet\Attestation;
use \SafetyNet\Verifier\VerifierType;
use \SafetyNet\Nonce;
use \SafetyNet\SafetyNetAttestationException;

$attestationStatement = new Statement('RAW-JWS-STATEMENT');
$nonce = new Nonce('Test-nonce');

try {

    $attestationConfig = new Config([
        Config::VERIFIER_TYPE => VerifierType::ONLINE(),
        Config::VERIFIER_TIMESTAMP_DIFF => 10 * 60 * 1000,
        Config::VERIFIER_CERTIFICATE_DIGEST_SHA256 => ['SHA-256-FINGERPRINT'],
        Config::VERIFIER_PACKAGE_NAME => ['APK-NAME-FOR-TEST'],
        Config::VERIFIER_API_KEY => 'GOOGLE-API-KEY',
        Config::VERIFIER_HARDWARE_BACKED => true,
    ]);

    $attestation = new Attestation($attestationConfig);

    if ($attestation->verity($nonce, $attestationStatement)) {
        echo 'Verification success!' . PHP_EOL;
    } else {
        echo 'Verification failed!' . PHP_EOL;
    }
} catch (SafetyNetAttestationException $e) {
    echo $e->getMessage() . PHP_EOL;
}
```

### Example for offline verification

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use \SafetyNet\Config\Config;
use \SafetyNet\Statement\Statement;
use \SafetyNet\Attestation;
use \SafetyNet\Verifier\VerifierType;
use \SafetyNet\Nonce;
use \SafetyNet\SafetyNetAttestationException;

$attestationStatement = new Statement('RAW-JWS-STATEMENT');
$nonce = new Nonce('Test-nonce');

try {

    $attestationConfig = new Config([
        Config::VERIFIER_TYPE => VerifierType::OFFLINE(),
        Config::VERIFIER_TIMESTAMP_DIFF => 10 * 60 * 1000,
        Config::VERIFIER_CERTIFICATE_DIGEST_SHA256 => ['SHA-256-FINGERPRINT'],
        Config::VERIFIER_PACKAGE_NAME => ['APK-NAME-FOR-TEST'],
        Config::VERIFIER_HARDWARE_BACKED => true,
    ]);

    $attestation = new Attestation($attestationConfig);

    if ($attestation->verity($nonce, $attestationStatement)) {
        echo 'Verification success!' . PHP_EOL;
    } else {
        echo 'Verification failed!' . PHP_EOL;
    }

} catch (SafetyNetAttestationException $e) {
    echo $e->getMessage() . PHP_EOL;
}
```