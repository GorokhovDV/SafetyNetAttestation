<?php

namespace SafetyNet\Statement\Exception;

use SafetyNet\Statement\StatementException;

class CertificateLoadError extends StatementException
{
    protected $message = "Certificate load error";
}