<?php

require_once __DIR__ . '/../config/database.php';

use Ramsey\Uuid\Uuid;

class ProductModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    // Get all products, optionally filtered by name
    public function getAll($search = null)
    {
        if ($search === null || $search === '') {
            $result = $this->db->query("SELECT * FROM products ORDER BY created_at DESC");
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        $stmt = $this->db->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY created_at DESC");
        $like = '%' . $search . '%';
        $stmt->bind_param('s', $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get one product by id
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Create new product
    public function create($data)
    {
        $id = Uuid::uuid4()->toString();

        $stmt = $this->db->prepare("INSERT INTO products (id, name, price, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssds', $id, $data['name'], $data['price'], $data['description']);
        $stmt->execute();

        return $id;
    }

    // Update existing product
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->bind_param('sdss', $data['name'], $data['price'], $data['description'], $id);
        return $stmt->execute();
    }

    // Delete product
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param('s', $id);
        return $stmt->execute();
    }
}
