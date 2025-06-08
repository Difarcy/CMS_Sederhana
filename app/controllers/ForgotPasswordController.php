<?php
class ForgotPasswordController extends Controller {
    public function index() {
        $this->view('forgot-password/index');
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            if (empty($email)) {
                $_SESSION['error'] = 'Email harus diisi.';
                header('Location: /forgot-password');
                exit();
            }
            $db = new Database();
            $user = $db->getUserByEmail($email);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $db->createPasswordReset($email, $token, $expiry);
                // Simulasi kirim email (tampilkan link reset di halaman sukses)
                $_SESSION['success'] = 'Link reset password: <a href="/reset-password?token=' . $token . '">Reset Password</a> (hanya untuk demo, seharusnya dikirim ke email)';
            } else {
                $_SESSION['error'] = 'Email tidak ditemukan.';
            }
            header('Location: /forgot-password');
            exit();
        }
    }
} 