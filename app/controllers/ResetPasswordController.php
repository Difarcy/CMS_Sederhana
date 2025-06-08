<?php
class ResetPasswordController extends Controller {
    public function index() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            $_SESSION['error'] = 'Token tidak valid.';
            header('Location: /login');
            exit();
        }
        $this->view('reset-password/index', ['token' => $token]);
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';
            if (empty($token) || empty($password) || empty($confirm)) {
                $_SESSION['error'] = 'Semua field harus diisi.';
                header('Location: /reset-password?token=' . urlencode($token));
                exit();
            }
            if ($password !== $confirm) {
                $_SESSION['error'] = 'Password tidak cocok.';
                header('Location: /reset-password?token=' . urlencode($token));
                exit();
            }
            $db = new Database();
            $reset = $db->getPasswordReset($token);
            if ($reset && strtotime($reset['expiry']) > time()) {
                $db->updatePassword($reset['email'], $password);
                $db->deletePasswordReset($token);
                $_SESSION['success'] = 'Password berhasil direset. Silakan login.';
                header('Location: /login');
                exit();
            } else {
                $_SESSION['error'] = 'Token tidak valid atau sudah kadaluarsa.';
                header('Location: /reset-password?token=' . urlencode($token));
                exit();
            }
        }
    }
} 