<?php

namespace App\Controllers;

class TransactionController extends BaseController {
    private $transactionModel;
    private $bookModel;
    private $memberModel;

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
        $this->transactionModel = $this->model('Transaction');
        $this->bookModel = $this->model('Book');
        $this->memberModel = $this->model('Member');
    }

    public function index() {
        $transactions = $this->transactionModel->getTransactions();
        $data = [
            'transactions' => $transactions
        ];
        $this->view('transactions/index', $data);
    }

    public function borrow() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'book_id' => trim($_POST['book_id'] ?? ''),
                'member_id' => trim($_POST['member_id'] ?? ''),
                'book_id_err' => '',
                'member_id_err' => '',
                'books' => $this->bookModel->getBooks(),
                'members' => $this->memberModel->getMembers()
            ];

            if (empty($data['book_id'])) { $data['book_id_err'] = 'Please select a book'; }
            if (empty($data['member_id'])) { $data['member_id_err'] = 'Please select a member'; }

            // Check if book has available copies
            if (empty($data['book_id_err'])) {
                $book = $this->bookModel->getBookById($data['book_id']);
                if ($book->available_copies <= 0) {
                    $data['book_id_err'] = 'No available copies for this book';
                }
            }

            if (empty($data['book_id_err']) && empty($data['member_id_err'])) {
                if ($this->transactionModel->borrowBook($data)) {
                    // Decrement available copies
                    $this->bookModel->decrementAvailableCopies($data['book_id']);
                    flash('transaction_msg', 'Book borrowed successfully');
                    header('Location: ' . URL_ROOT . '/transaction');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('transactions/borrow', $data);
            }
        } else {
            $data = [
                'book_id' => '', 'member_id' => '',
                'book_id_err' => '', 'member_id_err' => '',
                'books' => $this->bookModel->getBooks(),
                'members' => $this->memberModel->getMembers()
            ];
            $this->view('transactions/borrow', $data);
        }
    }

    public function return($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $transaction = $this->transactionModel->getTransactionById($id);
            if ($transaction && $transaction->status == 'borrowed') {
                
                // Calculate basic fine if overdue
                $fine = 0;
                $due = strtotime($transaction->due_date);
                $now = strtotime(date('Y-m-d'));
                if ($now > $due) {
                    $days_late = floor(($now - $due) / (60 * 60 * 24));
                    $fine = $days_late * 1.00; // $1.00 per day late
                }

                if ($this->transactionModel->returnBook($id, $fine)) {
                    // Increment available copies
                    $this->bookModel->incrementAvailableCopies($transaction->book_id);
                    flash('transaction_msg', 'Book returned successfully. Fine: $' . number_format($fine, 2));
                    header('Location: ' . URL_ROOT . '/transaction');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                header('Location: ' . URL_ROOT . '/transaction');
                exit;
            }
        } else {
            header('Location: ' . URL_ROOT . '/transaction');
            exit;
        }
    }
}
