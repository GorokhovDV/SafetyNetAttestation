<?php

namespace SafetyNet\Statement\Exception;

use SafetyNet\Statement\StatementException;

class CertificateCALoadError extends StatementException
{
    protected $message = "CA Certificate load error";
}