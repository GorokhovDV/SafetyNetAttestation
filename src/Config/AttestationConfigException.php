<?php
namespace SafetyNet\Config;

use SafetyNet\SafetyNetAttestationException;

class AttestationConfigException extends SafetyNetAttestationException {

    protected $message = 'Invalid Attestation config';
}