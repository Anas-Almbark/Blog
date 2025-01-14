<?php

require_once "./components/dbConnected.php";
require_once "./components/sqlCommand.php";
require_once "./components/dataValidator.php";

class Profile extends dbConnected
{
    private $id;
    private $name;
    private $password;
    private $confirmPassword;
    private $photo;

    public function __construct()
    {
        parent::__construct();


        if (isset($_GET["id"])) {
            $this->id = $_GET["id"];
            header('Content-Type: application/json');
            echo json_encode($this->getDataProfile());
            exit();
        }

        if (isset($_POST['updateProfile'])) {
            $this->id = $_POST['id'];
            $this->name = $_POST['name'];
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $this->photo = $this->uploadImage($_FILES['photo']); // new image
            } else {
                $this->photo = $_POST["old_img"]; // old image
            }
            $this->updateProfile();
        }

        if (isset($_POST['changePass'])) {
            $this->id = $_POST['id'];
            $this->password = $_POST['password'];
            $this->confirmPassword = $_POST['confirmPassword'];
            $this->changePassword();
        }
    }

    public function getDataProfile()
    {
        $sql = SqlCommand::selectById("users");
        $query = $this->getConnection()->prepare($sql);
        $query->execute([$this->id]);
        $record = $query->fetch(PDO::FETCH_ASSOC);
        return $record;
    }

    private function uploadImage($file)
    {
        $target_dir = "../images/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $unique_name = uniqid() . '.' . strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . $unique_name;

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return "images/" . $unique_name;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }

    public function updateProfile()
    {
        $data = [];
        $dataEx = [];

        if (!empty($this->name)) {
            array_push($data, "name");
            array_push($dataEx, $this->name);
        }
        if (!empty($this->photo)) {
            array_push($data, "photo");
            array_push($dataEx, $this->photo);
        }
        $sql = SqlCommand::update("users", $data);
        $query = $this->getConnection()->prepare($sql);
        $res = $query->execute([...$dataEx, $this->id]);

        if ($res) {
            echo "success";
        } else {
            echo "Failed to update profile";
        }
    }


    public function changePassword()
    {
        $dataValid = [$_POST['sql-oldPass'], $_POST['oldPassword'], $this->password, $this->confirmPassword];
        if (dataValidator::validatePass($dataValid)) {
            $sql = SqlCommand::update("users", ["password"]);
            $query = $this->getConnection()->prepare($sql);
            $res = $query->execute([password_hash($this->password, PASSWORD_BCRYPT), $this->id]);
            if ($res) {
                session_start();
                session_unset();
                session_destroy();
                header("Location: http://localhost/Blog/front-end/user/login.html");
            } else {
                echo "Failed to change password";
            }
        } else {
            echo "Invalid password";
        }
    }
}

new Profile();
