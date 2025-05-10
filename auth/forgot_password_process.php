<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    $db = new Database();
    $user = $db->findUserByEmail($email);
    
    if ($user) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Update user with reset token
        $db->updateUser($user['id'], [
            'reset_token' => $token,
            'token_expiry' => $expiry
        ]);
        
        // Store email in session for reset password page
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_token'] = $token;
        
        header("Location: ../reset-password.php");
        exit();
    } else {
        $_SESSION['error'] = "Email not found in our database";
        header("Location: ../forgot-password.php");
        exit();
    }
} else {
    header("Location: ../forgot-password.php");
    exit();
}
?> 