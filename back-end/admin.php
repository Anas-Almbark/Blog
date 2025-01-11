<?php

require_once "./components/dbConnected.php";
require_once "./components/sqlCommand.php";

class Admin extends dbConnected
{
    private $username;
    private $email;
    private $password;
    private $role;
    private $created_at;

    public function __construct()
    {
        parent::__construct();
        $this->username = $_POST['username'];
        $this->email = $_POST['email'];
        $this->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $this->role = "admin";
        $this->created_at = date('Y-m-d H:i:s');

        $this->createAdmin();
    }

    public function createAdmin()
    {
        $sql = SqlCommand::insert("admins", [
            "name",
            "email",
            "password",
            "role",
            "created_at"
        ]);
        $query = $this->getConnection()->prepare($sql);
        $result = $query->execute([$this->username, $this->email, $this->password, $this->role, $this->created_at]);
        if ($result) {
            header("Location: http://localhost/Blog/front-end/admin/index.html");
        } else {
            echo "Failed to create admin";
        }
    }
}
if (isset($_POST['submit'])) $admin = new Admin();
