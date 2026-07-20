<?php

namespace App\Models;

use App\Config\Database;

class Publisher {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getPublishers() {
        $this->db->query('SELECT * FROM publishers ORDER BY name ASC');
        return $this->db->resultSet();
    }

    public function getPublisherById($id) {
        $this->db->query('SELECT * FROM publishers WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addPublisher($data) {
        $this->db->query('INSERT INTO publishers (name, contact_email) VALUES (:name, :contact_email)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_email', $data['contact_email']);

        return $this->db->execute();
    }

    public function updatePublisher($data) {
        $this->db->query('UPDATE publishers SET name = :name, contact_email = :contact_email WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_email', $data['contact_email']);

        return $this->db->execute();
    }

    public function deletePublisher($id) {
        $this->db->query('DELETE FROM publishers WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
