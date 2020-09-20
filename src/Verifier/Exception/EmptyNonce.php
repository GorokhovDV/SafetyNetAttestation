<?php

namespace SafetyNet\Verifier\Exception;

use SafetyNet\Verifier\VerifierException;

class EmptyNonce extends VerifierException
{
    protected $message = "Empty nonce";
}