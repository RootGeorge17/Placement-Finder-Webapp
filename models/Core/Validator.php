<?php

namespace models\Core;

class Validator
{
    // Validates a string based on its length
    public static function string($value, $min = 1, $max = INF)
    {
        $value = trim($value);

        return strlen($value) >= $min && strlen($value) <= $max;
    }

    // Validates whether an input is an email or not
    public static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}