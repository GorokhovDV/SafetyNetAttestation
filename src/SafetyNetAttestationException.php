<?php

namespace SafetyNet;

use DomainException;

class SafetyNetAttestationException extends DomainException
{
    protected $message = 'SafetyNet Attestation failed!';
}