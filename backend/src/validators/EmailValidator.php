<?php

class EmailValidator
{
    public static function isValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function sanitize(string $email): string
    {
        return strtolower(trim($email));
    }
}
