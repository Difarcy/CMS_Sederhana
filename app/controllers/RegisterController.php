<?php

class RegisterController extends Controller {
    public function index() {
        $this->view('register/index');
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate input
                if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
                    throw new Exception("Semua field harus diisi.");
                }

                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Format email tidak valid.");
                }

                if (strlen($_POST['password']) < 6) {
                    throw new Exception("Password minimal 6 karakter.");
                }

                $db = new Database();

                if ($db->usernameExists($_POST['username'])) {
                    throw new Exception("Username sudah digunakan.");
                }

                if ($db->emailExists($_POST['email'])) {
                    throw new Exception("Email sudah digunakan.");
                }

                $user = [
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'role' => 'user',
                    'status' => 'active'
                ];

                if ($db->createUser($user['username'], $user['email'], $_POST['password'], $user['role'], $user['status'])) {
                    $_SESSION['success'] = "Registrasi berhasil. Silakan login.";
                    header("Location: /CMS_Sederhana/login");
                    exit();
                } else {
                    $_SESSION['error'] = "Registrasi gagal. Silakan coba lagi.";
                    header("Location: /CMS_Sederhana/register");
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header("Location: /CMS_Sederhana/register");
                exit();
            }
        }
    }
}