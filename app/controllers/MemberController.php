<?php

namespace App\Controllers;

class MemberController extends BaseController {
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
        $this->memberModel = $this->model('Member');
    }

    public function index() {
        $members = $this->memberModel->getMembers();
        $data = [
            'members' => $members
        ];
        $this->view('members/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'first_name_err' => '',
                'last_name_err' => ''
            ];

            if (empty($data['first_name'])) { $data['first_name_err'] = 'Please enter first name'; }
            if (empty($data['last_name'])) { $data['last_name_err'] = 'Please enter last name'; }

            if (empty($data['first_name_err']) && empty($data['last_name_err'])) {
                $userModel = $this->model('User');
                
                // Construct the name and email
                $fullName = trim($data['first_name'] . ' ' . $data['last_name']);
                
                // Use a default email if none provided, to ensure uniqueness
                $email = !empty($data['email']) ? $data['email'] : strtolower($data['first_name'] . '.' . $data['last_name'] . rand(10, 99) . '@example.com');
                
                $userData = [
                    'name' => $fullName,
                    'email' => $email,
                    'password' => password_hash('password', PASSWORD_DEFAULT),
                    'role_id' => 3
                ];
                
                if ($userModel->addUser($userData)) {
                    $newUserId = $userModel->getLastInsertId();
                    if ($this->memberModel->createMemberFromUser($newUserId, $fullName, $email, $data['phone'])) {
                        flash('member_msg', 'Member added successfully');
                        header('Location: ' . URL_ROOT . '/member');
                        exit;
                    } else {
                        die('Something went wrong creating the member');
                    }
                } else {
                    $data['last_name_err'] = 'Could not create User profile (Email might be taken)';
                    $this->view('members/create', $data);
                }
            } else {
                $this->view('members/create', $data);
            }
        } else {
            $data = [
                'first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '',
                'first_name_err' => '', 'last_name_err' => ''
            ];
            $this->view('members/create', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'first_name_err' => '',
                'last_name_err' => ''
            ];

            if (empty($data['first_name'])) { $data['first_name_err'] = 'Please enter first name'; }
            if (empty($data['last_name'])) { $data['last_name_err'] = 'Please enter last name'; }

            if (empty($data['first_name_err']) && empty($data['last_name_err'])) {
                if ($this->memberModel->updateMember($data)) {
                    flash('member_msg', 'Member updated successfully');
                    header('Location: ' . URL_ROOT . '/member');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('members/edit', $data);
            }
        } else {
            $member = $this->memberModel->getMemberById($id);
            if (!$member) {
                header('Location: ' . URL_ROOT . '/member');
                exit;
            }

            $data = [
                'id' => $member->id,
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'email' => $member->email,
                'phone' => $member->phone,
                'first_name_err' => '', 'last_name_err' => ''
            ];
            $this->view('members/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->memberModel->deleteMember($id)) {
                flash('member_msg', 'Member removed successfully');
                header('Location: ' . URL_ROOT . '/member');
                exit;
            } else {
                die('Something went wrong');
            }
        }
    }
}
