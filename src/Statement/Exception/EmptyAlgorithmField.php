<?php

namespace SafetyNet\Statement\Exception;

class EmptyAlgorithmField extends InvalidJWSFormat
{
    protected $message = "Empty algorithm field";
}