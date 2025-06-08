<?php
class UserDashboardController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
            header('Location: /CMS_Sederhana/login');
            exit;
        }
    }
    public function index() {
        $db = new Database();
        $data = [
            'recent_posts' => $db->getRecentPosts(),
            'user' => $_SESSION['username']
        ];
        $this->view('user-dashboard/index', $data);
    }
} 