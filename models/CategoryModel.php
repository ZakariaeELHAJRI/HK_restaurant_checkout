<?php
require_once(__DIR__.'/../config/database.php');

class CategoryModel {
    private $conn;
    private $id;
    private $name;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function createCategory() {
        $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $this->name);
        return $stmt->execute();
    }

    public function getCategoryById() {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateCategory() {
        $stmt = $this->conn->prepare("UPDATE categories SET name=? WHERE id = ?");
        $stmt->bind_param("si", $this->name, $this->id);
        return $stmt->execute();
    }

    public function deleteCategory() {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    public function getAllCategories() {
        $stmt = $this->conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        $result = $stmt->get_result();
        $categories = array();

        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }

    public function validateInput($data, $errorMessages) {
        foreach ($errorMessages as $field => $errorMessage) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return ErrorHandler::invalidInputError($errorMessage);
            }
        }

        return null; // No validation error
    }
}
