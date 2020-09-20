<?php

namespace SafetyNet\Verifier;

use SafetyNet\Statement\StatementException;

class VerifierException extends StatementException
{
    protected $message = 'Verification failed!';
}