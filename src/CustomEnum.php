<?php

namespace SafetyNet;

use MyCLabs\Enum\Enum;

class CustomEnum extends Enum
{
    public static function __callStatic($name, $arguments)
    {
        if (strpos($name, 'is') !== 0) {
            return parent::__callStatic($name, $arguments);
        }

        $name = substr($name, 2);

        if (count($arguments) == 0) {
            throw new \BadMethodCallException("Method is.. must contents value parameter in class " . static::class);
        }

        $valueForTest = $arguments[0];

        if (!($valueForTest instanceof self)) {
            throw new \BadMethodCallException("Method is.. must contents value type " . static::class . " parameter in class " . static::class);
        }

        return array_key_exists($name, self::toArray())
            && $valueForTest->getKey() == $name;
    }
}