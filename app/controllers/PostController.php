<?php

class PostController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $db = new Database();
        $data = [
            'posts' => $db->getAllPosts()
        ];
        $this->view('posts/index', $data);
    }

    public function create() {
        $db = new Database();
        $data = [
            'categories' => $db->getAllCategories(),
            'tags' => $db->getAllTags()
        ];
        $this->view('posts/create', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $data = [
                'title' => $_POST['title'],
                'slug' => $_POST['slug'],
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'],
                'featured_image' => '', // Handle file upload separately
                'status' => $_POST['status'],
                'author_id' => $_SESSION['user_id'],
                'category_id' => $_POST['category_id']
            ];
            
            // Handle file upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["featured_image"]["name"]);
                move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_file);
                $data['featured_image'] = $target_file;
            }

            $db->createPost($data);
            header('Location: /posts');
            exit;
        }
    }

    public function edit($id) {
        $db = new Database();
        $data = [
            'post' => $db->getPostById($id),
            'categories' => $db->getAllCategories(),
            'tags' => $db->getAllTags()
        ];
        $this->view('posts/edit', $data);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $data = [
                'title' => $_POST['title'],
                'slug' => $_POST['slug'],
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'],
                'featured_image' => $_POST['current_featured_image'], // Keep current image if not updated
                'status' => $_POST['status'],
                'category_id' => $_POST['category_id']
            ];

            // Handle file upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["featured_image"]["name"]);
                move_uploaded_file($_FILES["featured_image"]["tmp_name"], $target_file);
                $data['featured_image'] = $target_file;
            }

            $db->updatePost($id, $data);
            header('Location: /posts');
            exit;
        }
    }

    public function delete($id) {
        $db = new Database();
        $db->deletePost($id);
        header('Location: /posts');
        exit;
    }
}