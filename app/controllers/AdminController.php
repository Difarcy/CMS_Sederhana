<?php

class AdminController extends Controller {
    public function __construct() {
        // Middleware to check if user is logged in and is an admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $db = new Database();
        $data = [
            'post_count' => $db->countPosts(),
            'category_count' => $db->countCategories(),
            'tag_count' => $db->countTags(),
            'user_count' => $db->countUsers(),
            'recent_posts' => $db->getRecentPosts()
        ];
        $this->view('admin/index', $data);
    }
}