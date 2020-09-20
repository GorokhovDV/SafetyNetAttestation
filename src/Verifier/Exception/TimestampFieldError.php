<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class TimestampFieldError extends VerifierException
{
    protected $message = "TimestampMS field error";
}