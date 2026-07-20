<?php

namespace App\Controllers;

class PublisherController extends BaseController {
    private $publisherModel;

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
        $this->publisherModel = $this->model('Publisher');
    }

    public function index() {
        $publishers = $this->publisherModel->getPublishers();
        $data = [
            'publishers' => $publishers
        ];
        $this->view('publishers/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'contact_email' => trim($_POST['contact_email'] ?? ''),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter publisher name';
            }

            if (empty($data['name_err'])) {
                if ($this->publisherModel->addPublisher($data)) {
                    flash('publisher_msg', 'Publisher added successfully');
                    header('Location: ' . URL_ROOT . '/publisher');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('publishers/create', $data);
            }
        } else {
            $data = [
                'name' => '',
                'contact_email' => '',
                'name_err' => ''
            ];
            $this->view('publishers/create', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'contact_email' => trim($_POST['contact_email'] ?? ''),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter publisher name';
            }

            if (empty($data['name_err'])) {
                if ($this->publisherModel->updatePublisher($data)) {
                    flash('publisher_msg', 'Publisher updated successfully');
                    header('Location: ' . URL_ROOT . '/publisher');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('publishers/edit', $data);
            }
        } else {
            $publisher = $this->publisherModel->getPublisherById($id);
            if (!$publisher) {
                header('Location: ' . URL_ROOT . '/publisher');
                exit;
            }

            $data = [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'contact_email' => $publisher->contact_email,
                'name_err' => ''
            ];
            $this->view('publishers/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->publisherModel->deletePublisher($id)) {
                flash('publisher_msg', 'Publisher removed successfully');
                header('Location: ' . URL_ROOT . '/publisher');
                exit;
            } else {
                die('Something went wrong');
            }
        }
    }
}
