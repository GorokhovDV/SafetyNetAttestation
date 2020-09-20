<?php
namespace SafetyNet\Verifier\Exception;

use \SafetyNet\Verifier\VerifierException;

class CertificateHostnameError extends VerifierException
{
    protected $message = "Certificate hostname is invalid";
}