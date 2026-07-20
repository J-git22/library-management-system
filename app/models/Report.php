<?php

namespace App\Models;

use App\Config\Database;

class Report {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function countBooks() {
        $this->db->query('SELECT SUM(total_copies) as count FROM books');
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function countMembers() {
        $this->db->query('SELECT COUNT(*) as count FROM members');
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function countActiveBorrows() {
        $this->db->query('SELECT COUNT(*) as count FROM transactions WHERE status = "Borrowed"');
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    public function sumFines() {
        $this->db->query('SELECT SUM(fine_amount) as total FROM transactions');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getRecentTransactions() {
        $this->db->query('
            SELECT transactions.*, 
                   books.title as book_title, 
                   members.first_name, 
                   members.last_name 
            FROM transactions 
            JOIN books ON transactions.book_id = books.id 
            JOIN members ON transactions.member_id = members.id 
            ORDER BY transactions.id DESC 
            LIMIT 5
        ');
        return $this->db->resultSet();
    }
}
