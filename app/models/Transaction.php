<?php

namespace App\Models;

use App\Config\Database;

class Transaction {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getTransactions() {
        $this->db->query('
            SELECT transactions.*, 
                   books.title as book_title, 
                   members.first_name, 
                   members.last_name 
            FROM transactions 
            JOIN books ON transactions.book_id = books.id 
            JOIN members ON transactions.member_id = members.id 
            ORDER BY transactions.borrow_date DESC
        ');
        return $this->db->resultSet();
    }

    public function getTransactionById($id) {
        $this->db->query('SELECT * FROM transactions WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function borrowBook($data) {
        $this->db->query('INSERT INTO transactions (book_id, member_id, user_id, borrow_date, due_date) VALUES (:book_id, :member_id, :user_id, :borrow_date, :due_date)');
        
        $this->db->bind(':book_id', $data['book_id']);
        $this->db->bind(':member_id', $data['member_id']);
        $this->db->bind(':user_id', $_SESSION['user_id']); // the librarian who issued it
        $this->db->bind(':borrow_date', date('Y-m-d H:i:s'));
        
        // Default due date is 14 days from now
        $due_date = date('Y-m-d', strtotime('+14 days'));
        $this->db->bind(':due_date', $due_date);

        return $this->db->execute();
    }

    public function returnBook($id, $fine_amount = 0) {
        $this->db->query('UPDATE transactions SET return_date = :return_date, status = "returned", fine_amount = :fine_amount WHERE id = :id');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':return_date', date('Y-m-d H:i:s'));
        $this->db->bind(':fine_amount', $fine_amount);

        return $this->db->execute();
    }

    public function getTransactionsByMember($member_id) {
        $this->db->query('
            SELECT transactions.*, 
                   books.title as book_title
            FROM transactions 
            JOIN books ON transactions.book_id = books.id 
            WHERE transactions.member_id = :member_id
            ORDER BY transactions.borrow_date DESC
        ');
        $this->db->bind(':member_id', $member_id);
        return $this->db->resultSet();
    }

    public function getTotalFinesByMember($member_id) {
        $this->db->query('SELECT SUM(fine_amount) as total FROM transactions WHERE member_id = :member_id');
        $this->db->bind(':member_id', $member_id);
        $row = $this->db->single();
        return $row->total ? $row->total : 0;
    }
}
