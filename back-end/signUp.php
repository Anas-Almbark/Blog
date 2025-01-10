<?php

require_once "dbConnected.php";

class SignUp extends dbConnected
{
    protected $name = $_POST['name'];
    protected $email = $_POST['email'];
    protected $password = $_POST['password'];
    protected $confirmPassword = $_POST['confirmPassword'];

    public function __construct()
    {
        $this->signUp();
    }
    public function signUp()
    {
        $sql = SqlCommand::insert('users', ['name', 'email', 'password']);
        $query = $this->getConnection()->prepare($sql);
        $query->execute([$this->name, $this->email, $this->password]);
    }
}

if (isset($_POST['submit'])) $signUp = new SignUp();
