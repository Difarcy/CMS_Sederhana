<?php
require_once 'config/database.php';

// Fungsi untuk mendapatkan user berdasarkan ID
function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan semua posts
function getAllPosts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan post berdasarkan ID
function getPostById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk menambahkan post baru
function addPost($title, $content, $user_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, created_at) VALUES (?, ?, ?, NOW())");
    return $stmt->execute([$title, $content, $user_id]);
}

// Fungsi untuk mengupdate post
function updatePost($id, $title, $content) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    return $stmt->execute([$title, $content, $id]);
}

// Fungsi untuk menghapus post
function deletePost($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    return $stmt->execute([$id]);
}

// Fungsi untuk login
function login($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

// Fungsi untuk menambahkan user baru
function addUser($username, $password, $email) {
    global $pdo;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, created_at) VALUES (?, ?, ?, NOW())");
    return $stmt->execute([$username, $hashed_password, $email]);
}

// Fungsi untuk mendapatkan semua categories
function getAllCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk menambahkan category baru
function addCategory($name) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    return $stmt->execute([$name]);
}

// Fungsi untuk menghapus category
function deleteCategory($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    return $stmt->execute([$id]);
}
?> 