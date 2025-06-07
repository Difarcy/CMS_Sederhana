<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'csm_sederhana';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Check and create tables if not exists
            $this->checkAndCreateTagsTable();
            $this->checkAndCreateUsersTable();
            
            // Insert sample tags
            $this->insertSampleTags();
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    private function checkAndCreateTagsTable() {
        try {
            // Check if tags table exists
            $query = "SHOW TABLES LIKE 'tags'";
            $stmt = $this->conn->query($query);
            if ($stmt->rowCount() == 0) {
                // Create tags table
                $this->conn->exec("
                    CREATE TABLE IF NOT EXISTS tags (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        slug VARCHAR(255) NOT NULL UNIQUE,
                        description TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )
                ");
            }

            // Check if post_tags table exists
            $query = "SHOW TABLES LIKE 'post_tags'";
            $stmt = $this->conn->query($query);
            if ($stmt->rowCount() == 0) {
                // Create post_tags table
                $this->conn->exec("
                    CREATE TABLE IF NOT EXISTS post_tags (
                        post_id INT NOT NULL,
                        tag_id INT NOT NULL,
                        PRIMARY KEY (post_id, tag_id),
                        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
                        FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
                    )
                ");
            }
        } catch (PDOException $e) {
            error_log("Error creating tags tables: " . $e->getMessage());
        }
    }

    private function checkAndCreateUsersTable() {
        try {
            // Check if users table exists
            $query = "SHOW TABLES LIKE 'users'";
            $stmt = $this->conn->query($query);
            if ($stmt->rowCount() == 0) {
                // Create users table
                $this->conn->exec("
                    CREATE TABLE IF NOT EXISTS users (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        username VARCHAR(255) NOT NULL UNIQUE,
                        email VARCHAR(255) NOT NULL UNIQUE,
                        password VARCHAR(255) NOT NULL,
                        role ENUM('admin', 'user') DEFAULT 'user',
                        status ENUM('active', 'inactive') DEFAULT 'active',
                        reset_token VARCHAR(255) DEFAULT NULL,
                        reset_token_expiry DATETIME DEFAULT NULL,
                        remember_token VARCHAR(255) DEFAULT NULL,
                        remember_token_expiry DATETIME DEFAULT NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )
                ");
            } else {
                // Check if status column exists
                $query = "SHOW COLUMNS FROM users LIKE 'status'";
                $stmt = $this->conn->query($query);
                if ($stmt->rowCount() == 0) {
                    // Add status column
                    $this->conn->exec("
                        ALTER TABLE users 
                        ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' 
                        AFTER role
                    ");
                }
            }
        } catch (PDOException $e) {
            error_log("Error creating/updating users table: " . $e->getMessage());
        }
    }

    public function getUsers() {
        $stmt = $this->conn->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function addUser($user) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO users (id, username, email, password, created_at)
                VALUES (:id, :username, :email, :password, :created_at)
            ");
            return $stmt->execute([
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $user['password'],
                'created_at' => $user['created_at']
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function getUserByEmail($email) {
        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getUserByEmail: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByResetToken($token) {
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE reset_token = :token 
            AND reset_token_expiry > NOW()
        ");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function getUserByRememberToken($token) {
        $stmt = $this->conn->prepare("
            SELECT * FROM users 
            WHERE remember_token = :token 
            AND remember_token_expiry > NOW()
        ");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function updateUser($id, $data) {
        try {
            $query = "UPDATE users SET 
                     username = :username,
                     email = :email";
            
            // Only update password if provided
            if (isset($data['password'])) {
                $query .= ", password = :password";
            }
            
            // Only update role if provided
            if (isset($data['role'])) {
                $query .= ", role = :role";
            }
            
            // Only update status if provided
            if (isset($data['status'])) {
                $query .= ", status = :status";
            }
            
            $query .= " WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $params = [
                'id' => $id,
                'username' => $data['username'],
                'email' => $data['email']
            ];
            
            if (isset($data['password'])) {
                $params['password'] = $data['password'];
            }
            
            if (isset($data['role'])) {
                $params['role'] = $data['role'];
            }
            
            if (isset($data['status'])) {
                $params['status'] = $data['status'];
            }
            
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error in updateUser: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function clearExpiredTokens() {
        try {
            $stmt = $this->conn->prepare("
                UPDATE users 
                SET reset_token = NULL, 
                    reset_token_expiry = NULL,
                    remember_token = NULL,
                    remember_token_expiry = NULL
                WHERE (reset_token_expiry < NOW() OR remember_token_expiry < NOW())
            ");
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function emailExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function usernameExists($username) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    public function findUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function getSettings() {
        $settings = [];
        $stmt = $this->conn->query("SELECT setting_key, setting_value FROM settings");
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public function countPosts() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM posts");
        return $stmt->fetchColumn();
    }

    public function countCategories() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM categories");
        return $stmt->fetchColumn();
    }

    public function countTags() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM tags");
        return $stmt->fetchColumn();
    }

    public function countUsers() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }

    public function getRecentPosts($limit = 5) {
        try {
            $query = "SELECT p.*, u.username as author_name, c.name as category_name 
                     FROM posts p 
                     LEFT JOIN users u ON p.author_id = u.id 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     ORDER BY p.created_at ASC 
                     LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getRecentPosts: " . $e->getMessage());
            return [];
        }
    }

    public function getAllCategories() {
        try {
            $query = "SELECT * FROM categories ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllCategories: " . $e->getMessage());
            return [];
        }
    }

    public function getCategoryById($id) {
        try {
            $query = "SELECT * FROM categories WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCategoryById: " . $e->getMessage());
            return false;
        }
    }

    public function getCategoryBySlug($slug) {
        try {
            $query = "SELECT * FROM categories WHERE slug = :slug";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCategoryBySlug: " . $e->getMessage());
            return false;
        }
    }

    public function createCategory($data) {
        try {
            $query = "INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':description', $data['description']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in createCategory: " . $e->getMessage());
            return false;
        }
    }

    public function updateCategory($id, $data) {
        try {
            $query = "UPDATE categories SET name = :name, slug = :slug, description = :description WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':description', $data['description']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in updateCategory: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCategory($id) {
        try {
            $query = "DELETE FROM categories WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in deleteCategory: " . $e->getMessage());
            return false;
        }
    }

    public function countPostsByCategory($category_id) {
        try {
            $query = "SELECT COUNT(*) as count FROM posts WHERE category_id = :category_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Error in countPostsByCategory: " . $e->getMessage());
            return 0;
        }
    }

    public function getPostById($id) {
        try {
            $query = "SELECT p.*, u.username as author_name, c.name as category_name 
                     FROM posts p 
                     LEFT JOIN users u ON p.author_id = u.id 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     WHERE p.id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error in getPostById: " . $e->getMessage());
            return false;
        }
    }

    public function getAllPosts() {
        try {
            $query = "SELECT p.*, u.username as author_name, c.name as category_name 
                     FROM posts p 
                     LEFT JOIN users u ON p.author_id = u.id 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     ORDER BY p.created_at ASC";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getAllPosts: " . $e->getMessage());
            return [];
        }
    }

    public function createPost($data) {
        try {
            $query = "INSERT INTO posts (title, slug, content, excerpt, featured_image, status, author_id, category_id) 
                     VALUES (:title, :slug, :content, :excerpt, :featured_image, :status, :author_id, :category_id)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'excerpt' => $data['excerpt'],
                'featured_image' => $data['featured_image'],
                'status' => $data['status'],
                'author_id' => $data['author_id'],
                'category_id' => $data['category_id']
            ]);
        } catch (PDOException $e) {
            error_log("Error in createPost: " . $e->getMessage());
            return false;
        }
    }

    public function updatePost($id, $data) {
        try {
            $query = "UPDATE posts 
                     SET title = :title, 
                         slug = :slug, 
                         content = :content, 
                         excerpt = :excerpt, 
                         featured_image = :featured_image, 
                         status = :status, 
                         category_id = :category_id 
                     WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                'id' => $id,
                'title' => $data['title'],
                'slug' => $data['slug'],
                'content' => $data['content'],
                'excerpt' => $data['excerpt'],
                'featured_image' => $data['featured_image'],
                'status' => $data['status'],
                'category_id' => $data['category_id']
            ]);
        } catch (PDOException $e) {
            error_log("Error in updatePost: " . $e->getMessage());
            return false;
        }
    }

    public function deletePost($id) {
        try {
            // Get post data first to delete featured image
            $post = $this->getPostById($id);
            if ($post && $post['featured_image']) {
                $image_path = __DIR__ . '/../' . $post['featured_image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            // Delete post tags first (due to foreign key constraint)
            $stmt = $this->conn->prepare("DELETE FROM post_tags WHERE post_id = :id");
            $stmt->execute(['id' => $id]);

            // Delete post
            $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch(PDOException $e) {
            error_log("Error deleting post: " . $e->getMessage());
            return false;
        }
    }

    public function getAllTags() {
        try {
            $query = "SELECT * FROM tags ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllTags: " . $e->getMessage());
            return [];
        }
    }

    public function createTag($name, $slug, $description = '') {
        try {
            // Log the input values
            error_log("Attempting to create tag with name: $name, slug: $slug, description: $description");

            // Validate input
            if (empty($name) || empty($slug)) {
                error_log("Tag name and slug are required");
                return false;
            }

            // Check if tag with same slug already exists
            $checkQuery = "SELECT COUNT(*) FROM tags WHERE slug = :slug";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':slug', $slug);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() > 0) {
                error_log("Tag with slug '$slug' already exists");
                return false;
            }

            // Insert new tag
            $query = "INSERT INTO tags (name, slug, description) VALUES (:name, :slug, :description)";
            $stmt = $this->conn->prepare($query);
            
            // Log the prepared statement
            error_log("Prepared statement: $query");
            
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':description', $description);
            
            $result = $stmt->execute();
            
            if (!$result) {
                $error = $stmt->errorInfo();
                error_log("Failed to execute tag creation query. Error: " . print_r($error, true));
                return false;
            }
            
            error_log("Tag created successfully with ID: " . $this->conn->lastInsertId());
            return true;
        } catch (PDOException $e) {
            error_log("Error in createTag: " . $e->getMessage());
            error_log("Error code: " . $e->getCode());
            error_log("Error trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function updateTag($id, $name, $slug, $description = '') {
        try {
            $query = "UPDATE tags SET name = :name, slug = :slug, description = :description WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':slug', $slug);
            $stmt->bindParam(':description', $description);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in updateTag: " . $e->getMessage());
            return false;
        }
    }

    public function deleteTag($id) {
        try {
            // First delete all post-tag relationships
            $query = "DELETE FROM post_tags WHERE tag_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Then delete the tag
            $query = "DELETE FROM tags WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in deleteTag: " . $e->getMessage());
            return false;
        }
    }

    public function countPostsByTag($tag_id) {
        try {
            $query = "SELECT COUNT(*) as count FROM post_tags WHERE tag_id = :tag_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tag_id', $tag_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error in countPostsByTag: " . $e->getMessage());
            return 0;
        }
    }

    public function getTagBySlug($slug) {
        try {
            $query = "SELECT * FROM tags WHERE slug = :slug";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getTagBySlug: " . $e->getMessage());
            return false;
        }
    }

    public function getTagById($id) {
        try {
            $query = "SELECT * FROM tags WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getTagById: " . $e->getMessage());
            return false;
        }
    }

    public function getPostTags($post_id) {
        try {
            $query = "SELECT t.* FROM tags t
                      INNER JOIN post_tags pt ON t.id = pt.tag_id
                      WHERE pt.post_id = :post_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':post_id', $post_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getPostTags: " . $e->getMessage());
            return [];
        }
    }

    public function getAllUsers() {
        try {
            $query = "SELECT * FROM users ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllUsers: " . $e->getMessage());
            return [];
        }
    }

    public function getUserById($id) {
        try {
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            return false;
        }
    }

    public function createUser($username, $email, $password, $role = 'user', $status = 'active') {
        try {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO users (username, email, password, role, status, created_at) 
                     VALUES (:username, :email, :password, :role, :status, NOW())";
            $stmt = $this->conn->prepare($query);
            
            return $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashed_password,
                'role' => $role,
                'status' => $status
            ]);
        } catch (PDOException $e) {
            error_log("Error in createUser: " . $e->getMessage());
            return false;
        }
    }

    public function createPasswordReset($email, $token, $expiry) {
        // Delete any existing reset tokens for this email
        $stmt = $this->conn->prepare("DELETE FROM password_resets WHERE email = :email");
        $stmt->execute(['email' => $email]);

        // Create new reset token
        $stmt = $this->conn->prepare("INSERT INTO password_resets (email, token, expiry) VALUES (:email, :token, :expiry)");
        return $stmt->execute(['email' => $email, 'token' => $token, 'expiry' => $expiry]);
    }

    public function getPasswordReset($token) {
        $stmt = $this->conn->prepare("SELECT * FROM password_resets WHERE token = :token");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function deletePasswordReset($token) {
        $stmt = $this->conn->prepare("DELETE FROM password_resets WHERE token = :token");
        return $stmt->execute(['token' => $token]);
    }

    public function updatePassword($email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE email = :email");
            return $stmt->execute([
                'password' => $hashedPassword,
                'email' => $email
            ]);
        } catch (PDOException $e) {
            error_log("Error in updatePassword: " . $e->getMessage());
            return false;
        }
    }

    public function login($username, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username AND status = 'active'");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in login: " . $e->getMessage());
            return false;
        }
    }

    private function insertSampleTags() {
        try {
            $sampleTags = [
                ['name' => 'Teknologi', 'slug' => 'teknologi', 'description' => 'Artikel tentang teknologi terbaru dan inovasi'],
                ['name' => 'Pendidikan', 'slug' => 'pendidikan', 'description' => 'Artikel tentang dunia pendidikan dan pembelajaran'],
                ['name' => 'Bisnis', 'slug' => 'bisnis', 'description' => 'Artikel tentang bisnis, ekonomi, dan keuangan'],
                ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'description' => 'Artikel tentang kesehatan, medis, dan gaya hidup sehat'],
                ['name' => 'Olahraga', 'slug' => 'olahraga', 'description' => 'Artikel tentang olahraga dan aktivitas fisik'],
                ['name' => 'Seni', 'slug' => 'seni', 'description' => 'Artikel tentang seni, budaya, dan kreativitas'],
                ['name' => 'Travel', 'slug' => 'travel', 'description' => 'Artikel tentang perjalanan dan destinasi wisata'],
                ['name' => 'Kuliner', 'slug' => 'kuliner', 'description' => 'Artikel tentang makanan, minuman, dan resep'],
                ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Artikel tentang gaya hidup dan tren terkini'],
                ['name' => 'Entertainment', 'slug' => 'entertainment', 'description' => 'Artikel tentang hiburan, film, dan musik']
            ];

            $stmt = $this->conn->prepare("INSERT IGNORE INTO tags (name, slug, description) VALUES (:name, :slug, :description)");
            
            foreach ($sampleTags as $tag) {
                $stmt->execute([
                    'name' => $tag['name'],
                    'slug' => $tag['slug'],
                    'description' => $tag['description']
                ]);
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Error inserting sample tags: " . $e->getMessage());
            return false;
        }
    }
}

$db = new Database();
?> 