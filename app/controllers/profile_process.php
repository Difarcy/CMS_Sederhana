<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get user data
$db = new Database();
$current_user = $db->getUserByUsername($_SESSION['username']);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'update') {
            // Validate required fields
            if (empty($_POST['username']) || empty($_POST['email'])) {
                throw new Exception("Username and email are required.");
            }

            // Check if username exists (excluding current user)
            if ($_POST['username'] !== $current_user['username'] && $db->usernameExists($_POST['username'])) {
                throw new Exception("Username already exists.");
            }

            // Check if email exists (excluding current user)
            if ($_POST['email'] !== $current_user['email'] && $db->emailExists($_POST['email'])) {
                throw new Exception("Email already exists.");
            }

            // Update user data
            $update_data = [
                'username' => $_POST['username'],
                'email' => $_POST['email']
            ];

            // Only update password if provided
            if (!empty($_POST['password'])) {
                if (strlen($_POST['password']) < 6) {
                    throw new Exception("Password must be at least 6 characters long.");
                }
                if ($_POST['password'] !== $_POST['confirm_password']) {
                    throw new Exception("Passwords do not match.");
                }
                $update_data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            // Update user
            if ($db->updateUser($current_user['id'], $update_data)) {
                $_SESSION['success'] = "Profile updated successfully.";
                // Update session username if changed
                if ($_POST['username'] !== $current_user['username']) {
                    $_SESSION['username'] = $_POST['username'];
                }
            } else {
                throw new Exception("Failed to update profile.");
            }
        }
    }
} catch (Exception $e) {
    error_log("Profile Process Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    $_SESSION['error'] = $e->getMessage();
}

// Redirect back to profile page
header("Location: ../profile.php");
exit(); 