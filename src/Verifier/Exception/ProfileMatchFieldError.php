<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class ProfileMatchFieldError extends VerifierException
{
    protected $message = "Profile match field error";
}