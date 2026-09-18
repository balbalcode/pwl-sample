<?php

require_once __DIR__ . '/../config/database.php';

use Ramsey\Uuid\Uuid;

class DictionaryModel
{
    private $db;
    private $tableName = "dictionary";

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function getAll($search = null)
    {
        if ($search === null || $search === '') {
            $stmt = $this->db->query("SELECT * FROM " . $this->tableName);
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare("SELECT * FROM " . $this->tableName . " WHERE name LIKE ?");
        $stmt->execute(['%' . $search . '%']);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->tableName . " WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function create($data)
    {
        $id = Uuid::uuid4()->toString();

        $stmt = $this->db->prepare("INSERT INTO " . $this->tableName . " (id, name, script, result) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $data['name'], $data['script'], $data['result']]);

        return $id;
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE " . $this->tableName . " SET name = ?, script = ?, result = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['script'], $data['result'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM " . $this->tableName . " WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
