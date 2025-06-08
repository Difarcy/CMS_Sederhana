<?php

class LoginController extends Controller {
    public function index() {
        $this->view('login/index');
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate required fields
                if (empty($_POST['username']) || empty($_POST['password'])) {
                    throw new Exception("Username dan password harus diisi.");
                }

                $db = new Database();
                // Attempt login
                $user = $db->login($_POST['username'], $_POST['password']);
                if ($user) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect ke dashboard sesuai role
                    if ($user['role'] === 'admin') {
                        header("Location: /CMS_Sederhana/admin");
                    } else {
                        header("Location: /CMS_Sederhana/user-dashboard");
                    }
                    exit();
                } else {
                    throw new Exception("Username atau password salah.");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: /CMS_Sederhana/login");
                exit();
            }
        }
    }
}