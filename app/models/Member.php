<?php

namespace App\Models;

use App\Config\Database;

class Member {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getMembers() {
        $this->db->query('SELECT * FROM members ORDER BY first_name ASC, last_name ASC');
        return $this->db->resultSet();
    }

    public function getMemberById($id) {
        $this->db->query('SELECT * FROM members WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addMember($data) {
        $this->db->query('INSERT INTO members (first_name, last_name, email, phone) VALUES (:first_name, :last_name, :email, :phone)');
        
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);

        return $this->db->execute();
    }

    public function updateMember($data) {
        $this->db->query('UPDATE members SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);

        return $this->db->execute();
    }

    public function deleteMember($id) {
        $this->db->query('DELETE FROM members WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getMemberByUserId($user_id) {
        $this->db->query('SELECT * FROM members WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }

    public function createMemberFromUser($userId, $name, $email, $phone = '', $customId = '') {
        $parts = explode(' ', trim($name), 2);
        $firstName = $parts[0];
        $lastName = isset($parts[1]) ? $parts[1] : '';
        
        $memberIdString = '';
        
        if (!empty($customId)) {
            $memberIdString = trim($customId);
        } else {
            $suffixes = [24, 25, 26];
            $isUnique = false;
            
            while (!$isUnique) {
                $suffix = $suffixes[array_rand($suffixes)];
                $memberIdString = 'MEM-' . rand(1000, 9999) . '-' . $suffix;
                
                $this->db->query('SELECT id FROM members WHERE member_id_string = :member_id_string');
                $this->db->bind(':member_id_string', $memberIdString);
                $this->db->single();
                
                if ($this->db->rowCount() == 0) {
                    $isUnique = true;
                }
            }
        }
        
        $this->db->query('INSERT INTO members (user_id, member_id_string, first_name, last_name, email, phone, joined_date, status) VALUES (:user_id, :member_id_string, :first_name, :last_name, :email, :phone, :joined_date, "Active")');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':member_id_string', $memberIdString);
        $this->db->bind(':first_name', $firstName);
        $this->db->bind(':last_name', $lastName);
        $this->db->bind(':email', $email);
        $this->db->bind(':phone', $phone);
        $this->db->bind(':joined_date', date('Y-m-d H:i:s'));
        
        return $this->db->execute();
    }

    public function updateMemberDetailsFromUser($userId, $name, $email, $phone) {
        $parts = explode(' ', trim($name), 2);
        $firstName = $parts[0];
        $lastName = isset($parts[1]) ? $parts[1] : '';

        $this->db->query('UPDATE members SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone WHERE user_id = :user_id');
        $this->db->bind(':first_name', $firstName);
        $this->db->bind(':last_name', $lastName);
        $this->db->bind(':email', $email);
        $this->db->bind(':phone', $phone);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
}
