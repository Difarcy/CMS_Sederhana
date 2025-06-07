<?php

class CategoryController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $db = new Database();
        $data = [
            'categories' => $db->getAllCategories()
        ];
        $this->view('categories/index', $data);
    }

    public function create() {
        $this->view('categories/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $data = [
                'name' => $_POST['name'],
                'slug' => $_POST['slug'],
                'description' => $_POST['description']
            ];
            $db->createCategory($data);
            header('Location: /categories');
            exit;
        }
    }

    public function edit($id) {
        $db = new Database();
        $data = [
            'category' => $db->getCategoryById($id)
        ];
        $this->view('categories/edit', $data);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $data = [
                'name' => $_POST['name'],
                'slug' => $_POST['slug'],
                'description' => $_POST['description']
            ];
            $db->updateCategory($id, $data);
            header('Location: /categories');
            exit;
        }
    }

    public function delete($id) {
        $db = new Database();
        $db->deleteCategory($id);
        header('Location: /categories');
        exit;
    }
}