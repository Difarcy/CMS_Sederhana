<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password
    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long";
        header("Location: ../reset-password.php");
        exit();
    }
    
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: ../reset-password.php");
        exit();
    }
    
    if (!isset($_SESSION['reset_email'])) {
        $_SESSION['error'] = "Please request password reset first";
        header("Location: ../forgot-password.php");
        exit();
    }
    
    $db = new Database();
    $user = $db->getUserByEmail($_SESSION['reset_email']);
    
    if ($user) {
        // Update password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $db->updateUser($user['id'], [
            'password' => $hashed_password
        ]);
        
        // Clear reset session
        unset($_SESSION['reset_email']);
        
        $_SESSION['success'] = "Password has been reset successfully. Please login with your new password.";
        header("Location: ../login.php");
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