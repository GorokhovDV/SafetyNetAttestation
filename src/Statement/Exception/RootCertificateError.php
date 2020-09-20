<?php

namespace SafetyNet\Statement\Exception;

use SafetyNet\Statement\StatementException;

class RootCertificateError extends StatementException
{
    protected $message = "Root certificate error";
}