<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$db = new Database();

// Handle tag deletion
if (isset($_GET['id'])) {
    $tag_id = $_GET['id'];
    
    if ($db->deleteTag($tag_id)) {
        $_SESSION['success'] = "Tag deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete tag.";
    }
} else {
    $_SESSION['error'] = "Tag ID is required.";
}

header("Location: ../tags.php");
exit(); 