<?php

namespace App\Controllers;

class CategoryController extends BaseController {
    private $categoryModel;

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
        $this->categoryModel = $this->model('Category');
    }

    public function index() {
        $categories = $this->categoryModel->getCategories();
        $data = [
            'categories' => $categories
        ];
        $this->view('categories/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter category name';
            }

            if (empty($data['name_err'])) {
                if ($this->categoryModel->addCategory($data)) {
                    flash('category_msg', 'Category added successfully');
                    header('Location: ' . URL_ROOT . '/category');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('categories/create', $data);
            }
        } else {
            $data = [
                'name' => '',
                'description' => '',
                'name_err' => ''
            ];
            $this->view('categories/create', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter category name';
            }

            if (empty($data['name_err'])) {
                if ($this->categoryModel->updateCategory($data)) {
                    flash('category_msg', 'Category updated successfully');
                    header('Location: ' . URL_ROOT . '/category');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('categories/edit', $data);
            }
        } else {
            $category = $this->categoryModel->getCategoryById($id);
            if (!$category) {
                header('Location: ' . URL_ROOT . '/category');
                exit;
            }

            $data = [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'name_err' => ''
            ];
            $this->view('categories/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->categoryModel->deleteCategory($id)) {
                flash('category_msg', 'Category removed successfully');
                header('Location: ' . URL_ROOT . '/category');
                exit;
            } else {
                die('Something went wrong');
            }
        }
    }
}
