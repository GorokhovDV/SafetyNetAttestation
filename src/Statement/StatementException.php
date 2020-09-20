<?php

namespace SafetyNet\Statement;

use SafetyNet\SafetyNetAttestationException;

class StatementException extends SafetyNetAttestationException
{
    protected $message = 'Statement error!';
}