<?php

require_once __DIR__ . '/../config/database.php';

class ProductModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    // Get all products
    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM products ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get one product by id
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Create new product
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        $stmt->bind_param('sds', $data['name'], $data['price'], $data['description']);
        return $stmt->execute();
    }

    // Update existing product
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->bind_param('sdsi', $data['name'], $data['price'], $data['description'], $id);
        return $stmt->execute();
    }

    // Delete product
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
