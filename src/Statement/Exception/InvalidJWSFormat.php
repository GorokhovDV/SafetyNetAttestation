<?php
namespace SafetyNet\Statement\Exception;

use \SafetyNet\Statement\StatementException;

class InvalidJWSFormat extends StatementException
{
    protected $message = "Error while parsing JWS";
}