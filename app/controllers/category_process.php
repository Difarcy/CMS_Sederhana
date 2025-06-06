<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$db = new Database();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            // Create new category
            $name = $_POST['name'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($name) || empty($slug)) {
                $_SESSION['error'] = "Name and slug are required.";
                header("Location: ../categories.php");
                exit();
            }
            
            // Check if slug already exists
            if ($db->getCategoryBySlug($slug)) {
                $_SESSION['error'] = "A category with this slug already exists.";
                header("Location: ../categories.php");
                exit();
            }
            
            if ($db->createCategory([
                'name' => $name,
                'slug' => $slug,
                'description' => $description
            ])) {
                $_SESSION['success'] = "Category created successfully.";
            } else {
                $_SESSION['error'] = "Failed to create category.";
            }
            break;
            
        case 'update':
            // Update existing category
            $category_id = $_POST['category_id'] ?? '';
            $name = $_POST['name'] ?? '';
            $slug = $_POST['slug'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($category_id) || empty($name) || empty($slug)) {
                $_SESSION['error'] = "Category ID, name and slug are required.";
                header("Location: ../categories.php");
                exit();
            }
            
            // Check if slug already exists for other categories
            $existing_category = $db->getCategoryBySlug($slug);
            if ($existing_category && $existing_category['id'] != $category_id) {
                $_SESSION['error'] = "A category with this slug already exists.";
                header("Location: ../categories.php");
                exit();
            }
            
            if ($db->updateCategory($category_id, [
                'name' => $name,
                'slug' => $slug,
                'description' => $description
            ])) {
                $_SESSION['success'] = "Category updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update category.";
            }
            break;
    }
    
    header("Location: ../categories.php");
    exit();
} 