<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class CheckSignatureException extends VerifierException
{
    protected $message = "Signature is invalid";
}