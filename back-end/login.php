<?php

require_once './components/dbConnected.php';
require_once './components/sqlCommand.php';
require_once './components/error.php';
class Login extends dbConnected
{
    private $email;
    private $password;
    public function __construct()
    {
        parent::__construct();
        $this->email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $this->password = $_POST['password'];
        $this->login();
    }

    public function login()
    {
        try {
            $sql = SqlCommand::select2Tables('users', 'admins');
            $query = $this->getConnection()->prepare($sql);
            $query->execute([$this->email, $this->email]);
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                if (password_verify($this->password, $user['password'])) {
                    session_start();
                    $_SESSION['admin'] = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                    ];
                    if ($user['role'] == 'user') {
                        header('Location: http://localhost/Blog/front-end/user/');
                        exit;
                    } else {
                        header('Location: http://localhost/Blog/front-end/admin/');
                        exit;
                    }
                } else {
                    echo "invalid password";
                }
            } else {
                echo "User not found";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "An error occurred. Please try again later.";
        }
    }
}

if (isset($_POST['submit'])) {
    $login = new Login();
}
