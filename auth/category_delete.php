<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$db = new Database();

// Handle category deletion
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    
    // Check if category exists
    $category = $db->getCategoryById($category_id);
    if (!$category) {
        $_SESSION['error'] = "Category not found.";
        header("Location: ../categories.php");
        exit();
    }
    
    // Check if category has posts
    if ($db->countPostsByCategory($category_id) > 0) {
        $_SESSION['error'] = "Cannot delete category that has posts. Please reassign or delete the posts first.";
        header("Location: ../categories.php");
        exit();
    }
    
    // Delete category
    if ($db->deleteCategory($category_id)) {
        $_SESSION['success'] = "Category deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete category.";
    }
} else {
    $_SESSION['error'] = "Category ID is required.";
}

header("Location: ../categories.php");
exit(); 