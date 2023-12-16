<?php

namespace Bitress\CrudWrapper;

use PDO;
use PDOException;

class CrudWrapper
{
    private $db;

    public function __construct($host, $dbname, $username, $password)
    {
        // Connect to the database
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->db = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function create($table, $data)
    {
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute(array_values($data));
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            die("Create operation failed: " . $e->getMessage());
        }
    }

    public function read($table, $conditions = [])
    {
        $sql = "SELECT * FROM $table";
        $values = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $values[] = $value;
            }
            $sql .= implode(' AND ', $where);
        }

        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute($values);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Read operation failed: " . $e->getMessage());
        }
    }

    public function update($table, $data, $conditions)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
        }
        $set = implode(', ', $set);

        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = ?";
        }
        $where = implode(' AND ', $where);

        $sql = "UPDATE $table SET $set WHERE $where";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute(array_merge(array_values($data), array_values($conditions)));
        } catch (PDOException $e) {
            die("Update operation failed: " . $e->getMessage());
        }
    }

    public function delete($table, $conditions)
    {
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = ?";
        }
        $where = implode(' AND ', $where);

        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute(array_values($conditions));
        } catch (PDOException $e) {
            die("Delete operation failed: " . $e->getMessage());
        }
    }
}
