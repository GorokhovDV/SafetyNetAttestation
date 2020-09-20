<?php

namespace SafetyNet;

class Nonce
{
    private string $value;

    public function __construct(string $nonce)
    {
        $this->value = $nonce;
    }

    public function isEqual(Nonce $nonce): bool
    {
        return strcmp(base64_decode($this->value), base64_decode($nonce->getValue())) === 0;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}