<?php

namespace App\Models;

use App\Config\Database;

class Author {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAuthors() {
        $this->db->query('SELECT * FROM authors ORDER BY name ASC');
        return $this->db->resultSet();
    }

    public function getAuthorById($id) {
        $this->db->query('SELECT * FROM authors WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addAuthor($data) {
        $this->db->query('INSERT INTO authors (name, bio) VALUES (:name, :bio)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':bio', $data['bio']);

        return $this->db->execute();
    }

    public function updateAuthor($data) {
        $this->db->query('UPDATE authors SET name = :name, bio = :bio WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':bio', $data['bio']);

        return $this->db->execute();
    }

    public function deleteAuthor($id) {
        $this->db->query('DELETE FROM authors WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
