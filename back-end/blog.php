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
        if (isset($_POST['submit'])) {
            $this->title = $_POST['title'];
            $this->content = $_POST['content'];
            $this->author_id = $_SESSION['admin']['id'];
            $this->category_id = $_POST['category_id'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $this->img = $this->uploadImage();
            } else {
                $this->img = "images/default.jpg";
            }
            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = date('Y-m-d H:i:s');
        }

        if (isset($_GET['getBlogs'])) {
            header('Content-Type: application/json');
            echo json_encode($this->getAllBlogs());
            exit();
        }
        if (isset($_POST['deleteBlog'])) {
            $this->deleteBlog($_POST['deleteBlog']);
        }
    }

    private function uploadImage()
    {

        $uploadDir = '../images/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            echo "success in upload";
            return $uploadFile;
        } else {
            echo "error uploading";
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


    public function getAllBlogs()
    {
        $sql = SqlCommand::selectWithJoin("posts", "categories", "category_id", "posts.*, categories.name as category_name");
        $query = $this->getConnection()->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteBlog()
    {
        $sql = SqlCommand::delete("posts");
        $query = $this->getConnection()->prepare($sql);
        $res = $query->execute([$_POST['deleteBlog']]);
        if ($res) {
            echo "Blog deleted successfully";
            header("Location: http://localhost/Blog/front-end/admin/blogs/blogs.html?getBlogs=true");
        } else {
            echo "Failed to delete blog";
        }
    }
}

if (isset($_POST['submit'])) {
    $blog = new Blog();
    $blog->createBlog();
}

new Blog();
