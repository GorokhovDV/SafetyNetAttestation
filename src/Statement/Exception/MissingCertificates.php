<?php

namespace SafetyNet\Statement\Exception;

use SafetyNet\Statement\StatementException;

class MissingCertificates extends StatementException
{
    protected $message = "Missing certificate";
}