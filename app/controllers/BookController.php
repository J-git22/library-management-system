<?php

namespace App\Controllers;

class BookController extends BaseController {
    private $bookModel;
    private $authorModel;
    private $publisherModel;
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
        $this->bookModel = $this->model('Book');
        $this->authorModel = $this->model('Author');
        $this->publisherModel = $this->model('Publisher');
        $this->categoryModel = $this->model('Category');
    }

    public function index() {
        $books = $this->bookModel->getBooks();
        $data = [
            'books' => $books
        ];
        $this->view('books/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'isbn' => trim($_POST['isbn'] ?? ''),
                'author_id' => trim($_POST['author_id'] ?? ''),
                'publisher_id' => trim($_POST['publisher_id'] ?? ''),
                'category_id' => trim($_POST['category_id'] ?? ''),
                'published_year' => trim($_POST['published_year'] ?? ''),
                'total_copies' => trim($_POST['total_copies'] ?? ''),
                'title_err' => '',
                'isbn_err' => '',
                'total_copies_err' => '',
                // relations
                'authors' => $this->authorModel->getAuthors(),
                'publishers' => $this->publisherModel->getPublishers(),
                'categories' => $this->categoryModel->getCategories()
            ];

            if (empty($data['title'])) { $data['title_err'] = 'Please enter title'; }
            if (empty($data['isbn'])) { $data['isbn_err'] = 'Please enter ISBN'; }
            if (empty($data['total_copies']) || !is_numeric($data['total_copies'])) { $data['total_copies_err'] = 'Please enter valid total copies'; }

            if (empty($data['title_err']) && empty($data['isbn_err']) && empty($data['total_copies_err'])) {
                if ($this->bookModel->addBook($data)) {
                    flash('book_msg', 'Book added successfully');
                    header('Location: ' . URL_ROOT . '/book');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('books/create', $data);
            }
        } else {
            $data = [
                'title' => '', 'isbn' => '', 'author_id' => '', 'publisher_id' => '', 'category_id' => '',
                'published_year' => '', 'total_copies' => '',
                'title_err' => '', 'isbn_err' => '', 'total_copies_err' => '',
                // relations
                'authors' => $this->authorModel->getAuthors(),
                'publishers' => $this->publisherModel->getPublishers(),
                'categories' => $this->categoryModel->getCategories()
            ];
            $this->view('books/create', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'title' => trim($_POST['title'] ?? ''),
                'isbn' => trim($_POST['isbn'] ?? ''),
                'author_id' => trim($_POST['author_id'] ?? ''),
                'publisher_id' => trim($_POST['publisher_id'] ?? ''),
                'category_id' => trim($_POST['category_id'] ?? ''),
                'published_year' => trim($_POST['published_year'] ?? ''),
                'total_copies' => trim($_POST['total_copies'] ?? ''),
                'available_copies' => trim($_POST['available_copies'] ?? ''),
                'title_err' => '',
                'isbn_err' => '',
                'total_copies_err' => '',
                // relations
                'authors' => $this->authorModel->getAuthors(),
                'publishers' => $this->publisherModel->getPublishers(),
                'categories' => $this->categoryModel->getCategories()
            ];

            if (empty($data['title'])) { $data['title_err'] = 'Please enter title'; }
            if (empty($data['isbn'])) { $data['isbn_err'] = 'Please enter ISBN'; }
            if (empty($data['total_copies']) || !is_numeric($data['total_copies'])) { $data['total_copies_err'] = 'Please enter valid total copies'; }

            if (empty($data['title_err']) && empty($data['isbn_err']) && empty($data['total_copies_err'])) {
                if ($this->bookModel->updateBook($data)) {
                    flash('book_msg', 'Book updated successfully');
                    header('Location: ' . URL_ROOT . '/book');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('books/edit', $data);
            }
        } else {
            $book = $this->bookModel->getBookById($id);
            if (!$book) {
                header('Location: ' . URL_ROOT . '/book');
                exit;
            }

            $data = [
                'id' => $book->id,
                'title' => $book->title,
                'isbn' => $book->isbn,
                'author_id' => $book->author_id,
                'publisher_id' => $book->publisher_id,
                'category_id' => $book->category_id,
                'published_year' => $book->published_year,
                'total_copies' => $book->total_copies,
                'available_copies' => $book->available_copies,
                'title_err' => '', 'isbn_err' => '', 'total_copies_err' => '',
                // relations
                'authors' => $this->authorModel->getAuthors(),
                'publishers' => $this->publisherModel->getPublishers(),
                'categories' => $this->categoryModel->getCategories()
            ];
            $this->view('books/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->bookModel->deleteBook($id)) {
                flash('book_msg', 'Book removed successfully');
                header('Location: ' . URL_ROOT . '/book');
                exit;
            } else {
                die('Something went wrong');
            }
        }
    }
}
