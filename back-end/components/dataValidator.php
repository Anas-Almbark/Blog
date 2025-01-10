<?php

class dataValidator
{
    public static function validateUser($data)
    {
        list($name, $email, $password) = $data;
        if (
            filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($name) && !empty($password)
        ) {
            return true;
        }
        return false;
    }
}
