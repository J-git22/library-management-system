<?php

namespace App\Models;

use App\Config\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();
        
        if (!$row) return false;

        $hashed_password = $row->password;
        
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Get all users
    public function getUsers() {
        $this->db->query('SELECT users.*, roles.name as role_name FROM users JOIN roles ON users.role_id = roles.id ORDER BY users.created_at DESC');
        return $this->db->resultSet();
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add User
    public function addUser($data) {
        $this->db->query('INSERT INTO users (name, email, password, role_id) VALUES (:name, :email, :password, :role_id)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role_id', $data['role_id']);

        return $this->db->execute();
    }

    // Update User
    public function updateUser($data) {
        if (!empty($data['password'])) {
            $this->db->query('UPDATE users SET name = :name, email = :email, password = :password, role_id = :role_id WHERE id = :id');
            $this->db->bind(':password', $data['password']);
        } else {
            $this->db->query('UPDATE users SET name = :name, email = :email, role_id = :role_id WHERE id = :id');
        }
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role_id', $data['role_id']);

        return $this->db->execute();
    }

    // Delete User
    public function deleteUser($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateLastLogin($id) {
        $this->db->query('UPDATE users SET last_login = NOW() WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
}
