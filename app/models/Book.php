<?php

namespace App\Models;

use App\Config\Database;

class Book {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getBooks() {
        $this->db->query('
            SELECT books.*, 
                   authors.name as author_name, 
                   publishers.name as publisher_name, 
                   categories.name as category_name 
            FROM books 
            JOIN authors ON books.author_id = authors.id 
            JOIN publishers ON books.publisher_id = publishers.id 
            JOIN categories ON books.category_id = categories.id 
            ORDER BY books.created_at DESC
        ');
        return $this->db->resultSet();
    }

    public function getBookById($id) {
        $this->db->query('SELECT * FROM books WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addBook($data) {
        $this->db->query('INSERT INTO books (title, isbn, author_id, publisher_id, category_id, published_year, total_copies, available_copies) VALUES (:title, :isbn, :author_id, :publisher_id, :category_id, :published_year, :total_copies, :available_copies)');
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':isbn', $data['isbn']);
        $this->db->bind(':author_id', $data['author_id']);
        $this->db->bind(':publisher_id', $data['publisher_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':published_year', $data['published_year']);
        $this->db->bind(':total_copies', $data['total_copies']);
        $this->db->bind(':available_copies', $data['total_copies']);

        return $this->db->execute();
    }

    public function updateBook($data) {
        $this->db->query('UPDATE books SET title = :title, isbn = :isbn, author_id = :author_id, publisher_id = :publisher_id, category_id = :category_id, published_year = :published_year, total_copies = :total_copies, available_copies = :available_copies WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':isbn', $data['isbn']);
        $this->db->bind(':author_id', $data['author_id']);
        $this->db->bind(':publisher_id', $data['publisher_id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':published_year', $data['published_year']);
        $this->db->bind(':total_copies', $data['total_copies']);
        $this->db->bind(':available_copies', $data['available_copies']);

        return $this->db->execute();
    }

    public function deleteBook($id) {
        $this->db->query('DELETE FROM books WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function decrementAvailableCopies($id) {
        $this->db->query('UPDATE books SET available_copies = available_copies - 1 WHERE id = :id AND available_copies > 0');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function incrementAvailableCopies($id) {
        $this->db->query('UPDATE books SET available_copies = available_copies + 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getRecommendedBooks($member_id) {
        // Find most borrowed category
        $this->db->query('
            SELECT books.category_id, COUNT(*) as count 
            FROM transactions 
            JOIN books ON transactions.book_id = books.id 
            WHERE transactions.member_id = :member_id 
            GROUP BY books.category_id 
            ORDER BY count DESC 
            LIMIT 1
        ');
        $this->db->bind(':member_id', $member_id);
        $topCategory = $this->db->single();

        if ($topCategory && $topCategory->category_id) {
            // Get books from this category that haven't been borrowed by this member
            $this->db->query('
                SELECT books.*, authors.name as author_name 
                FROM books 
                JOIN authors ON books.author_id = authors.id 
                WHERE books.category_id = :category_id 
                AND books.id NOT IN (
                    SELECT book_id FROM transactions WHERE member_id = :member_id
                )
                ORDER BY RAND() LIMIT 3
            ');
            $this->db->bind(':category_id', $topCategory->category_id);
            $this->db->bind(':member_id', $member_id);
            $books = $this->db->resultSet();
            
            if (count($books) > 0) return $books;
        }

        // Fallback: 3 random books
        $this->db->query('
            SELECT books.*, authors.name as author_name 
            FROM books 
            JOIN authors ON books.author_id = authors.id 
            ORDER BY RAND() LIMIT 3
        ');
        return $this->db->resultSet();
    }
}
