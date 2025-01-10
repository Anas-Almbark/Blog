<?php

require_once "./components/dbConnected.php";

class dataValidator extends dbConnected
{
    public static function validateUser($data)
    {
        list($name, $email, $password, $confirmPassword) = $data;
        $errors = [];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        if (empty($name)) {
            $errors[] = "Name is required";
        }
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match";
        }
        $db = new PDO("mysql:host=localhost;dbname=blog", "root", "");
        $query = $db->prepare("SELECT email FROM users WHERE email = ? UNION SELECT email FROM admins WHERE email = ?");
        $query->execute([$email, $email]);
        $user = $query->fetch();
        if ($user) {
            $errors[] = "Email already exists";
        }

        if (!empty($errors)) {
            $errorsJson = json_encode($errors);
            header("Location: http://localhost/Blog/back-end/components/error.php?errors=$errorsJson");
            exit();
        }

        return true;
    }
}
