<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class GoogleRequestError extends VerifierException
{
    protected $message = "Google request error";
}