<?php
class ProfileController extends Controller {
    public function index() {
        $this->view('profile/index');
    }
    public function logout() {
        session_destroy();
        session_start();
        $_SESSION['success'] = 'Anda berhasil logout.';
        header('Location: /CMS_Sederhana/login');
        exit;
    }
} 