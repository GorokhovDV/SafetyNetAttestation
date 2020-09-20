<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class CertificateChainError extends VerifierException
{
    protected $message = "Certificate chain error";
}