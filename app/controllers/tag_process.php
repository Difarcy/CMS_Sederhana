<?php
session_start();
require_once '../config/database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$db = new Database();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    error_log("Processing tag action: " . $action);
    
    switch ($action) {
        case 'create':
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            
            error_log("Received tag data - Name: $name, Slug: $slug, Description: $description");
            
            // Validate input
            if (empty($name)) {
                $_SESSION['error'] = "Tag name is required.";
                error_log("Error: Tag name is empty");
                header("Location: ../tags.php");
                exit();
            }
            
            if (empty($slug)) {
                $_SESSION['error'] = "Tag slug is required.";
                error_log("Error: Tag slug is empty");
                header("Location: ../tags.php");
                exit();
            }
            
            // Sanitize slug
            $slug = strtolower($slug);
            $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
            $slug = preg_replace('/-+/', '-', $slug);
            $slug = trim($slug, '-');
            
            error_log("Sanitized slug: $slug");
            
            // Check if tag with same slug exists
            $existingTag = $db->getTagBySlug($slug);
            if ($existingTag) {
                $_SESSION['error'] = "A tag with this slug already exists.";
                error_log("Error: Tag with slug '$slug' already exists");
                header("Location: ../tags.php");
                exit();
            }
            
            try {
                error_log("Attempting to create tag...");
                if ($db->createTag($name, $slug, $description)) {
                    $_SESSION['success'] = "Tag created successfully.";
                    error_log("Tag created successfully");
                } else {
                    $_SESSION['error'] = "Failed to create tag. Please try again.";
                    error_log("Failed to create tag");
                }
            } catch (Exception $e) {
                error_log("Error creating tag: " . $e->getMessage());
                error_log("Error trace: " . $e->getTraceAsString());
                $_SESSION['error'] = "An error occurred while creating the tag. Please try again.";
            }
            break;
            
        case 'update':
            $tag_id = $_POST['tag_id'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $slug = trim($_POST['slug'] ?? '');
            $description = trim($_POST['description'] ?? '');
            
            error_log("Updating tag - ID: $tag_id, Name: $name, Slug: $slug");
            
            // Validate input
            if (empty($tag_id)) {
                $_SESSION['error'] = "Tag ID is required.";
                error_log("Error: Tag ID is empty");
                header("Location: ../tags.php");
                exit();
            }
            
            if (empty($name)) {
                $_SESSION['error'] = "Tag name is required.";
                error_log("Error: Tag name is empty");
                header("Location: ../tags.php");
                exit();
            }
            
            if (empty($slug)) {
                $_SESSION['error'] = "Tag slug is required.";
                error_log("Error: Tag slug is empty");
                header("Location: ../tags.php");
                exit();
            }
            
            // Sanitize slug
            $slug = strtolower($slug);
            $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
            $slug = preg_replace('/-+/', '-', $slug);
            $slug = trim($slug, '-');
            
            error_log("Sanitized slug: $slug");
            
            // Check if tag with same slug exists (excluding current tag)
            $existingTag = $db->getTagBySlug($slug);
            if ($existingTag && $existingTag['id'] != $tag_id) {
                $_SESSION['error'] = "A tag with this slug already exists.";
                error_log("Error: Tag with slug '$slug' already exists");
                header("Location: ../tags.php");
                exit();
            }
            
            try {
                error_log("Attempting to update tag...");
                if ($db->updateTag($tag_id, $name, $slug, $description)) {
                    $_SESSION['success'] = "Tag updated successfully.";
                    error_log("Tag updated successfully");
                } else {
                    $_SESSION['error'] = "Failed to update tag. Please try again.";
                    error_log("Failed to update tag");
                }
            } catch (Exception $e) {
                error_log("Error updating tag: " . $e->getMessage());
                error_log("Error trace: " . $e->getTraceAsString());
                $_SESSION['error'] = "An error occurred while updating the tag. Please try again.";
            }
            break;
            
        default:
            $_SESSION['error'] = "Invalid action.";
            error_log("Error: Invalid action '$action'");
            break;
    }
}

header("Location: ../tags.php");
exit(); 