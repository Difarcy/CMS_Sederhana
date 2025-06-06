<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    
    // Get form data
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $action = $_POST['action'] ?? 'publish';
    $author_id = $_SESSION['user_id'];

    // Set status based on action
    if ($action === 'draft') {
        $status = 'draft';
    } else {
        $status = $_POST['status'] ?? 'published';
    }

    // Generate slug from title
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // Handle featured image upload
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $upload_path)) {
                $featured_image = 'uploads/' . $new_filename;
            }
        }
    }

    // Create post data array
    $post_data = [
        'title' => $title,
        'slug' => $slug,
        'content' => $content,
        'excerpt' => $excerpt,
        'featured_image' => $featured_image,
        'status' => $status,
        'author_id' => $author_id,
        'category_id' => $category_id
    ];

    // Handle post creation/update
    if (isset($_POST['post_id'])) {
        // Update existing post
        $success = $db->updatePost($_POST['post_id'], $post_data);
        $message = $success ? 'Post updated successfully' : 'Error updating post';
    } else {
        // Create new post
        $success = $db->createPost($post_data);
        $message = $success ? 'Post created successfully' : 'Error creating post';
    }

    // Set session message
    $_SESSION[$success ? 'success' : 'error'] = $message;
    
    // Redirect back to posts page
    header("Location: ../posts.php");
    exit();
} else {
    header("Location: ../posts.php");
    exit();
} 