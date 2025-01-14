<?php

require_once './components/dbConnected.php';
require_once './components/sqlCommand.php';
require_once './components/dataValidator.php';

class SignUp extends dbConnected
{
    protected $name;
    protected $email;
    protected $password;
    protected $confirmPassword;
    protected $photo;
    protected $role;
    protected $created_at;

    public function __construct()
    {
        parent::__construct();
        $this->name = $_POST['username'];
        $this->email = $_POST['email'];
        $this->password = $_POST['password'];
        $this->confirmPassword = $_POST['confirmed_password'];
        $this->photo = "default.jpg";
        $this->role = "user";
        $this->created_at = date('Y-m-d H:i:s');

        $this->signUp();
    }

    public function signUp()
    {
        if (dataValidator::validateUser([$this->name, $this->email, $this->password, $this->confirmPassword])) {
            $sql = SqlCommand::insert('users', ['name', 'email', 'password', 'photo', 'role', 'created_at']);
            $query = $this->getConnection()->prepare($sql);
            $res = $query->execute([$this->name, $this->email, password_hash($this->password, PASSWORD_DEFAULT), $this->photo, $this->role, $this->created_at]);
            if ($res) {
                header("location: http://localhost/Blog/front-end/user/login.html");
            }
        }
    }
}

if (isset($_POST['submit'])) $signUp = new SignUp();
