<?php

require_once './components/dbConnected.php';
require_once './components/sqlCommand.php';

class Blog extends dbConnected
{
    private $title;
    private $content;
    private $author_id;
    private $img;
    private $created_at;
    private $updated_at;
    private $category_id;

    public function __construct()
    {
        session_start();
        parent::__construct();
        $this->title = $_POST['title'];
        $this->content = $_POST['content'];
        $this->author_id = $_SESSION['admin']['id'];
        $this->category_id = $_POST['category_id'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $this->img = $this->uploadImage($_FILES['image']);
        } else {
            $this->img = "uploads/default.jpg";
        }
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);

        if ($check !== false) {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit();
            }
        } else {
            echo "File is not an image.";
            exit();
        }
    }

    public function createBlog()
    {
        $sql = SqlCommand::insert("posts", [
            "title",
            "content",
            "img",
            "created_at",
            "updated_at",
            "admin_id",
            "category_id"
        ]);
        $query = $this->getConnection()->prepare($sql);
        $result = $query->execute([
            $this->title,
            $this->content,
            $this->img,
            $this->created_at,
            $this->updated_at,
            $this->author_id,
            $this->category_id
        ]);

        if ($result) {
            echo "Blog created successfully";
            header('Location: http://localhost/Blog/front-end/admin/blogs/blogs.html');
        } else {
            echo "Failed to create blog";
        }
    }
}

if (isset($_POST['submit'])) {
    $blog = new Blog();
    $blog->createBlog();
}
