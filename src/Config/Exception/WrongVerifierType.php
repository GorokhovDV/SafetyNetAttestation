<?php
namespace SafetyNet\Config\Exception;

use SafetyNet\Config\AttestationConfigException;

class WrongVerifierType extends AttestationConfigException
{
    protected $message = "VerifierType is not set in config array or it's not implements SafetyNet\VerifierType";
}