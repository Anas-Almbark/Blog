<?php

require_once './components/dbConnected.php';
require_once './components/sqlCommand.php';

class Category extends dbConnected
{
    private $category_name;
    private $category_id;

    public function __construct()
    {
        parent::__construct();
        if (isset($_POST['category']) && !isset($_POST['updateCategory'])) {
            $this->category_name = $_POST['category'];
            $this->addCategory();
        }
        if (isset($_GET['getCategories'])) {
            header('Content-Type: application/json');
            echo json_encode($this->getCategory());
            exit();
        }
        if (isset($_POST['deleteCategory'])) {
            $this->category_id = $_POST['deleteCategory'];
            $this->deleteCategory();
        }
        if (isset($_POST['updateCategory'])) {
            $this->category_id = $_POST['id'];
            $this->category_name = $_POST['category'];
            $this->updateCategory();
        }
    }

    public function addCategory()
    {
        $sql = SqlCommand::insert('categories', ['name'], [$this->category_name]);
        $query = $this->getConnection()->prepare($sql);
        $f =  $query->execute([$this->category_name]);
        if ($f) {
            echo "Category added successfully";
            header('Location: http://localhost/Blog/front-end/admin/categories/categories.html');
        } else {
            echo "Category not added";
        }
    }

    public function getCategory()
    {
        $sql = SqlCommand::select('categories');
        $query = $this->getConnection()->prepare($sql);
        $query->execute();
        $categories = $query->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    }

    public function deleteCategory()
    {
        $sql = SqlCommand::delete('categories', 'id', $this->category_id);
        $query = $this->getConnection()->prepare($sql);
        $result =  $query->execute([$this->category_id]);
        if ($result) {
            header('Location: http://localhost/Blog/front-end/admin/categories/categories.html');
        } else {
            echo "Category not deleted";
        }
    }

    public function updateCategory()
    {
        $sql = SqlCommand::update('categories', ['name'], 'id');
        $query = $this->getConnection()->prepare($sql);
        $result = $query->execute([$this->category_name, $this->category_id]);
        if ($result) {
            echo "Category updated successfully";
            header('Location: http://localhost/Blog/front-end/admin/categories/categories.html');
        } else {
            echo "Category not updated";
        }
    }
}

if (isset($_POST['send'])) $category = new Category();
if (isset($_GET['getCategories'])) $category = new Category();
if (isset($_POST['deleteCategory'])) $category = new Category();
if (isset($_POST['updateCategory'])) $category = new Category();
