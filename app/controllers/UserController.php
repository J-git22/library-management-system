<?php

namespace App\Controllers;

class UserController extends BaseController {
    private $userModel;

    public function __construct() {
        if (!isLoggedIn() || $_SESSION['role_id'] == 3) {
            flash('auth_error', 'Access Denied', 'alert alert-danger');
            header('Location: ' . URL_ROOT . '/dashboard');
            exit;
        }
        $this->userModel = $this->model('User');
    }

    public function index() {
        $users = $this->userModel->getUsers();
        $data = [
            'users' => $users
        ];
        $this->view('users/index', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'member_id_string' => trim($_POST['member_id_string'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'role_id' => trim($_POST['role_id'] ?? ''),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'member_id_err' => ''
            ];

            // Validation
            if (empty($data['name'])) { $data['name_err'] = 'Please enter name'; }
            if (empty($data['email'])) { 
                $data['email_err'] = 'Please enter email'; 
            } else {
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }
            if (empty($data['password']) || strlen($data['password']) < 6) { 
                $data['password_err'] = 'Please enter a valid password (min 6 characters)'; 
            }
            
            if (!empty($data['member_id_string'])) {
                $memberModel = $this->model('Member');
                $db = new Database();
                $db->query('SELECT id FROM members WHERE member_id_string = :member_id_string');
                $db->bind(':member_id_string', $data['member_id_string']);
                $db->single();
                if ($db->rowCount() > 0) {
                    $data['member_id_err'] = 'This Member ID is already taken';
                }
            }

            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['member_id_err'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                if ($this->userModel->addUser($data)) {
                    // Create Member profile automatically
                    $newUserId = $this->userModel->getLastInsertId();
                    $memberModel = $this->model('Member');
                    $memberModel->createMemberFromUser($newUserId, $data['name'], $data['email'], $data['phone'], $data['member_id_string']);

                    flash('user_msg', 'User and Member profile added successfully');
                    header('Location: ' . URL_ROOT . '/user');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/create', $data);
            }
        } else {
            $data = [
                'name' => '', 'email' => '', 'password' => '', 'role_id' => 2,
                'name_err' => '', 'email_err' => '', 'password_err' => ''
            ];
            $this->view('users/create', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'role_id' => trim($_POST['role_id'] ?? ''),
                'name_err' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // Validation
            if (empty($data['name'])) { $data['name_err'] = 'Please enter name'; }
            if (empty($data['email'])) { $data['email_err'] = 'Please enter email'; }

            if (empty($data['name_err']) && empty($data['email_err'])) {
                if (!empty($data['password'])) {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
                
                if ($this->userModel->updateUser($data)) {
                    // Sync details to Member table
                    $memberModel = $this->model('Member');
                    $memberModel->updateMemberDetailsFromUser($id, $data['name'], $data['email'], $data['phone']);

                    flash('user_msg', 'User updated successfully');
                    header('Location: ' . URL_ROOT . '/user');
                    exit;
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/edit', $data);
            }
        } else {
            $user = $this->userModel->getUserById($id);
            if (!$user) {
                header('Location: ' . URL_ROOT . '/user');
                exit;
            }

            // Fetch Member to get phone
            $memberModel = $this->model('Member');
            $member = $memberModel->getMemberByUserId($id);

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $member ? $member->phone : '',
                'role_id' => $user->role_id,
                'name_err' => '', 'email_err' => '', 'password_err' => ''
            ];
            $this->view('users/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->deleteUser($id)) {
                flash('user_msg', 'User removed successfully');
                header('Location: ' . URL_ROOT . '/user');
                exit;
            } else {
                die('Something went wrong');
            }
        }
    }
}
