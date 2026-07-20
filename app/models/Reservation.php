<?php

namespace App\Models;

use App\Config\Database;

class Reservation {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getReservations() {
        $this->db->query('
            SELECT reservations.*, 
                   books.title as book_title, 
                   members.first_name, 
                   members.last_name 
            FROM reservations 
            JOIN books ON reservations.book_id = books.id 
            JOIN members ON reservations.member_id = members.id 
            ORDER BY reservations.reservation_date DESC
        ');
        return $this->db->resultSet();
    }

    public function getReservationById($id) {
        $this->db->query('SELECT * FROM reservations WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function createReservation($data) {
        $this->db->query('INSERT INTO reservations (book_id, member_id, reservation_date, status) VALUES (:book_id, :member_id, :reservation_date, "pending")');
        
        $this->db->bind(':book_id', $data['book_id']);
        $this->db->bind(':member_id', $data['member_id']);
        $this->db->bind(':reservation_date', date('Y-m-d'));

        return $this->db->execute();
    }

    public function updateStatus($id, $status) {
        $this->db->query('UPDATE reservations SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    public function getReservationsByMember($member_id) {
        $this->db->query('
            SELECT reservations.*, books.title as book_title 
            FROM reservations 
            JOIN books ON reservations.book_id = books.id 
            WHERE reservations.member_id = :member_id
            ORDER BY reservations.reservation_date DESC
        ');
        $this->db->bind(':member_id', $member_id);
        return $this->db->resultSet();
    }
}
