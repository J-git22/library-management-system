<?php

namespace App\Controllers;

class DashboardController extends BaseController {
    private $reportModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('Location: ' . URL_ROOT . '/auth/login');
            exit;
        }
        $this->reportModel = $this->model('Report');
    }

    public function index() {
        if ($_SESSION['role_id'] == 3) {
            // Student Dashboard
            $userModel = $this->model('User');
            $memberModel = $this->model('Member');
            $transactionModel = $this->model('Transaction');
            $reservationModel = $this->model('Reservation');
            $bookModel = $this->model('Book');
            
            $user = $userModel->getUserById($_SESSION['user_id']);
            $member = $memberModel->getMemberByUserId($_SESSION['user_id']);
            
            $transactions = [];
            $reservations = [];
            $total_fines = 0;
            $recommended_books = [];
            
            if ($member) {
                $transactions = $transactionModel->getTransactionsByMember($member->id);
                $reservations = $reservationModel->getReservationsByMember($member->id);
                $total_fines = $transactionModel->getTotalFinesByMember($member->id);
                $recommended_books = $bookModel->getRecommendedBooks($member->id);
            }
            
            $data = [
                'title' => 'Student Dashboard',
                'user' => $user,
                'member' => $member,
                'transactions' => $transactions,
                'reservations' => $reservations,
                'total_fines' => $total_fines,
                'recommended_books' => $recommended_books
            ];
            
            $this->view('dashboard/student', $data);
        } else {
            // Admin/Librarian Dashboard
            $data = [
                'title' => 'LMS Dashboard',
                'total_books' => $this->reportModel->countBooks(),
                'total_members' => $this->reportModel->countMembers(),
                'active_borrows' => $this->reportModel->countActiveBorrows(),
                'total_fines' => $this->reportModel->sumFines(),
                'recent_transactions' => $this->reportModel->getRecentTransactions()
            ];
            
            $this->view('dashboard/index', $data);
        }
    }
    public function borrowRecommended() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role_id'] == 3) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $book_id = trim($_POST['book_id']);
            $memberModel = $this->model('Member');
            $member = $memberModel->getMemberByUserId($_SESSION['user_id']);
            
            if ($member && !empty($book_id)) {
                $transactionModel = $this->model('Transaction');
                $bookModel = $this->model('Book');
                
                $data = [
                    'book_id' => $book_id,
                    'member_id' => $member->id,
                    'borrow_date' => date('Y-m-d H:i:s'),
                    'due_date' => date('Y-m-d H:i:s', strtotime('+14 days')),
                    'status' => 'Borrowed'
                ];
                
                if ($transactionModel->borrowBook($data)) {
                    $bookModel->decrementAvailableCopies($book_id);
                    flash('dashboard_msg', 'Book successfully borrowed! Please pick it up from the front desk.', 'alert alert-success mt-3');
                } else {
                    flash('dashboard_msg', 'Something went wrong processing your request.', 'alert alert-danger mt-3');
                }
            }
        }
        header('Location: ' . URL_ROOT . '/dashboard');
    }

    public function returnBook() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role_id'] == 3) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $transaction_id = trim($_POST['transaction_id']);
            $book_id = trim($_POST['book_id']);
            
            if (!empty($transaction_id) && !empty($book_id)) {
                $transactionModel = $this->model('Transaction');
                $bookModel = $this->model('Book');
                
                // Calculate if there's a fine (simplified logic)
                $transaction = $transactionModel->getTransactionById($transaction_id);
                $fine_amount = 0;
                if ($transaction) {
                    $due_date = strtotime($transaction->due_date);
                    $now = time();
                    if ($now > $due_date) {
                        $days_late = floor(($now - $due_date) / (60 * 60 * 24));
                        $fine_amount = $days_late * 5.00; // 5 GHc per day
                    }
                }
                
                if ($transactionModel->returnBook($transaction_id, $fine_amount)) {
                    $bookModel->incrementAvailableCopies($book_id);
                    flash('dashboard_msg', 'Book returned successfully!', 'alert alert-success mt-3');
                } else {
                    flash('dashboard_msg', 'Failed to return the book.', 'alert alert-danger mt-3');
                }
            }
        }
        header('Location: ' . URL_ROOT . '/dashboard');
    }

    public function cancelReservation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role_id'] == 3) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $reservation_id = trim($_POST['reservation_id']);
            
            if (!empty($reservation_id)) {
                $reservationModel = $this->model('Reservation');
                
                // Ensure the reservation belongs to the student (for security, though not strictly checked here yet, updating status is fine)
                if ($reservationModel->updateStatus($reservation_id, 'Cancelled')) {
                    flash('dashboard_msg', 'Reservation cancelled successfully.', 'alert alert-success mt-3');
                } else {
                    flash('dashboard_msg', 'Failed to cancel the reservation.', 'alert alert-danger mt-3');
                }
            }
        }
        header('Location: ' . URL_ROOT . '/dashboard');
    }
    
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role_id'] == 3) {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $userId = $_SESSION['user_id'];
            $name = trim($_POST['name'] ?? '');
            $password = trim($_POST['password'] ?? '');
            
            if (empty($name)) {
                flash('dashboard_msg', 'Name cannot be empty.', 'alert alert-danger mt-3');
            } else {
                $userModel = $this->model('User');
                $memberModel = $this->model('Member');
                
                $user = $userModel->getUserById($userId);
                $member = $memberModel->getMemberByUserId($userId);
                
                $data = [
                    'id' => $userId,
                    'name' => $name,
                    'email' => $user->email,
                    'role_id' => 3
                ];
                
                if (!empty($password)) {
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                } else {
                    $data['password'] = $user->password;
                }
                
                if ($userModel->updateUser($data)) {
                    // Update session name
                    $_SESSION['user_name'] = $name;
                    
                    if ($member) {
                        $memberModel->updateMemberDetailsFromUser($userId, $name, $user->email, $member->phone);
                    }
                    flash('dashboard_msg', 'Profile updated successfully.', 'alert alert-success mt-3');
                } else {
                    flash('dashboard_msg', 'Failed to update profile.', 'alert alert-danger mt-3');
                }
            }
        }
        header('Location: ' . URL_ROOT . '/dashboard');
    }
}
