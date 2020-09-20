<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class ApkDigestShaError extends VerifierException
{
    protected $message = "Invalid ApkDigestSha";
}