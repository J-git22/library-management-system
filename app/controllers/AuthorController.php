<?php

namespace App\Controllers;

class AuthorController extends BaseController {
    private $authorModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/auth/login');
            exit;
        }
        if ($_SESSION['role_id'] == 3) {
            flash('auth_error', 'Access Denied', 'alert alert-danger');
            header('Location: ' . URL_ROOT . '/dashboard');
            exit;
        }
        $this->authorModel = $this->model('Author');
    }

    public function index() {
        $authors = $this->authorModel->getAuthors();
        $data = [
            'authors' => $authors
        ];
        $this->view('authors/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'bio' => trim($_POST['bio'] ?? ''),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter author name';
            }

            if (empty($data['name_err'])) {
                if ($this->authorModel->addAuthor($data)) {
                    flash('author_msg', 'Author added successfully');
                    header('Location: ' . URL_ROOT . '/author');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('authors/create', $data);
            }
        } else {
            $data = [
                'name' => '',
                'bio' => '',
                'name_err' => ''
            ];
            $this->view('authors/create', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'bio' => trim($_POST['bio'] ?? ''),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter author name';
            }

            if (empty($data['name_err'])) {
                if ($this->authorModel->updateAuthor($data)) {
                    flash('author_msg', 'Author updated successfully');
                    header('Location: ' . URL_ROOT . '/author');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('authors/edit', $data);
            }
        } else {
            $author = $this->authorModel->getAuthorById($id);
            if (!$author) {
                header('Location: ' . URL_ROOT . '/author');
                exit;
            }

            $data = [
                'id' => $author->id,
                'name' => $author->name,
                'bio' => $author->bio,
                'name_err' => ''
            ];
            $this->view('authors/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->authorModel->deleteAuthor($id)) {
                flash('author_msg', 'Author removed successfully');
                header('Location: ' . URL_ROOT . '/author');
                exit;
            } else {
                die('Something went wrong');
            }
        }
    }
}
