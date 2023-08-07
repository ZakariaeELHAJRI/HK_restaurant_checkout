<?php
require_once(__DIR__.'/../config/database.php');

class LigneCommandModel {
    private $conn;
    private $id;
    private $quantity;
    private $ligne_command_id;
    private $product_id;

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

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setLigneCommandId($ligne_command_id) {
        $this->ligne_command_id = $ligne_command_id;
    }

    public function getLigneCommandId() {
        return $this->ligne_command_id;
    }

    public function setProductId($product_id) {
        $this->product_id = $product_id;
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function createLigneCommand() {
        $stmt = $this->conn->prepare("INSERT INTO ligne_commands (quantity, ligne_command_id, product_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $this->quantity, $this->ligne_command_id, $this->product_id);
        return $stmt->execute();
    }

    public function getLigneCommandById() {
        $stmt = $this->conn->prepare("SELECT * FROM ligne_commands WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateLigneCommand() {
        $stmt = $this->conn->prepare("UPDATE ligne_commands SET quantity=?, ligne_command_id=?, product_id=? WHERE id = ?");
        $stmt->bind_param("iiii", $this->quantity, $this->ligne_command_id, $this->product_id, $this->id);
        return $stmt->execute();
    }

    public function deleteLigneCommand() {
        $stmt = $this->conn->prepare("DELETE FROM ligne_commands WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    public function getAllLigneCommands() {
        $stmt = $this->conn->prepare("SELECT * FROM ligne_commands");
        $stmt->execute();
        $result = $stmt->get_result();
        $ligneCommands = array();

        while ($row = $result->fetch_assoc()) {
            $ligneCommands[] = $row;
        }

        return $ligneCommands;
    }

    public function validateInput($data) {
        // Add validation rules for input data
        $rules = array(
            'quantity' => 'Quantity is required.',
            'ligne_command_id' => 'Ligne command ID is required.',
            'product_id' => 'Product ID is required.'
        );

        foreach ($rules as $field => $message) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return ErrorHandler::invalidInputError($message);
            }
        }

        return null; // No validation error
    }
}
