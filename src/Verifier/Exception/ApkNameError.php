<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class ApkNameError extends VerifierException
{
    protected $message = "Invalid ApkName";
}