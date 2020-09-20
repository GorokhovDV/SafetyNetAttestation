<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class BasicIntegrityFieldError extends VerifierException
{
    protected $message = "BasicIntegrity field is false or null";
}