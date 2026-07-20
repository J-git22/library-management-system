<?php

namespace App\Controllers;

class ReservationController extends BaseController {
    private $reservationModel;
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
        $this->reservationModel = $this->model('Reservation');
        $this->bookModel = $this->model('Book');
        $this->memberModel = $this->model('Member');
    }

    public function index() {
        $reservations = $this->reservationModel->getReservations();
        $data = [
            'reservations' => $reservations
        ];
        $this->view('reservations/index', $data);
    }

    public function create() {
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

            // Allow reservation only if book available copies is 0 (optional, but good rule)
            if (empty($data['book_id_err'])) {
                $book = $this->bookModel->getBookById($data['book_id']);
                if ($book->available_copies > 0) {
                    $data['book_id_err'] = 'Book is currently available. Member should borrow it directly instead of reserving.';
                }
            }

            if (empty($data['book_id_err']) && empty($data['member_id_err'])) {
                if ($this->reservationModel->createReservation($data)) {
                    flash('reservation_msg', 'Reservation placed successfully');
                    header('Location: ' . URL_ROOT . '/reservation');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('reservations/create', $data);
            }
        } else {
            $data = [
                'book_id' => '', 'member_id' => '',
                'book_id_err' => '', 'member_id_err' => '',
                'books' => $this->bookModel->getBooks(),
                'members' => $this->memberModel->getMembers()
            ];
            $this->view('reservations/create', $data);
        }
    }

    public function fulfill($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reservation = $this->reservationModel->getReservationById($id);
            if ($reservation && $reservation->status == 'pending') {
                $book = $this->bookModel->getBookById($reservation->book_id);
                if ($book->available_copies > 0) {
                    if ($this->reservationModel->updateStatus($id, 'completed')) {
                        flash('reservation_msg', 'Reservation marked as completed (Remember to issue the book in Circulation!).');
                        header('Location: ' . URL_ROOT . '/reservation');
                        exit;
                    }
                } else {
                    flash('reservation_msg', 'Cannot fulfill: Book is still not available.', 'alert alert-danger');
                    header('Location: ' . URL_ROOT . '/reservation');
                    exit;
                }
            }
        }
        header('Location: ' . URL_ROOT . '/reservation');
    }

    public function cancel($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->reservationModel->updateStatus($id, 'canceled')) {
                flash('reservation_msg', 'Reservation canceled');
                header('Location: ' . URL_ROOT . '/reservation');
                exit;
            } else {
                die('Something went wrong');
            }
        }
    }
}
