<?php

class UserController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $db = new Database();
        $data = [
            'users' => $db->getAllUsers()
        ];
        $this->view('users/index', $data);
    }

    public function create() {
        $this->view('users/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $db->createUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'], $_POST['status']);
            header('Location: /users');
            exit;
        }
    }

    public function edit($id) {
        $db = new Database();
        $data = [
            'user' => $db->getUserById($id)
        ];
        $this->view('users/edit', $data);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'role' => $_POST['role'],
                'status' => $_POST['status']
            ];
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            $db->updateUser($id, $data);
            header('Location: /users');
            exit;
        }
    }

    public function delete($id) {
        $db = new Database();
        $db->deleteUser($id);
        header('Location: /users');
        exit;
    }
}