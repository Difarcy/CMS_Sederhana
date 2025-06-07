<?php

class TagController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index() {
        $db = new Database();
        $data = [
            'tags' => $db->getAllTags()
        ];
        $this->view('tags/index', $data);
    }

    public function create() {
        $this->view('tags/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $db->createTag($_POST['name'], $_POST['slug'], $_POST['description']);
            header('Location: /tags');
            exit;
        }
    }

    public function edit($id) {
        $db = new Database();
        $data = [
            'tag' => $db->getTagById($id)
        ];
        $this->view('tags/edit', $data);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $db->updateTag($id, $_POST['name'], $_POST['slug'], $_POST['description']);
            header('Location: /tags');
            exit;
        }
    }

    public function delete($id) {
        $db = new Database();
        $db->deleteTag($id);
        header('Location: /tags');
        exit;
    }
}