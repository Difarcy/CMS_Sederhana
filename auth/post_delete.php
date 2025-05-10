<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if post ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Post ID is required";
    header("Location: ../posts.php");
    exit();
}

$db = new Database();

// Delete post
if ($db->deletePost($_GET['id'])) {
    $_SESSION['success'] = "Post successfully deleted";
} else {
    $_SESSION['error'] = "Failed to delete post";
}

header("Location: ../posts.php");
exit(); 