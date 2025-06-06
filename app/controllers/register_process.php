<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required";
        $_SESSION['form_data'] = $_POST;
        header("Location: ../register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        $_SESSION['form_data'] = $_POST;
        header("Location: ../register.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long";
        $_SESSION['form_data'] = $_POST;
        header("Location: ../register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        $_SESSION['form_data'] = $_POST;
        header("Location: ../register.php");
        exit();
    }

    // Check if username or email already exists
    if ($db->usernameExists($username)) {
        $_SESSION['error'] = "Username already exists";
        $_SESSION['form_data'] = $_POST;
        header("Location: ../register.php");
        exit();
    }

    if ($db->emailExists($email)) {
        $_SESSION['error'] = "Email already exists";
        $_SESSION['form_data'] = $_POST;
        header("Location: ../register.php");
        exit();
    }

    // Create new user
    $new_user = [
        'id' => uniqid(),
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s')
    ];

    if ($db->addUser($new_user)) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: ../login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        $_SESSION['form_data'] = $_POST;
        header("Location: ../register.php");
        exit();
    }
} else {
    header("Location: ../register.php");
    exit();
}
?> 