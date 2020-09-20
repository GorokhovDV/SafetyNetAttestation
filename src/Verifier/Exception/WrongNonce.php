<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class WrongNonce extends VerifierException
{
    protected $message = "Wrong nonce error";
}