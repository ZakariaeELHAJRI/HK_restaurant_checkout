<?php
require_once(__DIR__.'/../config/database.php');

class CommandModel {
    private $conn;
    private $id;
    private $creation_date;
    private $user_id;

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

    public function setCreationDate($creation_date) {
        $this->creation_date = $creation_date;
    }

    public function getCreationDate() {
        return $this->creation_date;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function createCommand() {
        $stmt = $this->conn->prepare("INSERT INTO commands (creation_date, user_id) VALUES (?, ?)");
        $stmt->bind_param("si", $this->creation_date, $this->user_id);
        return $stmt->execute();
    }

    public function getCommandById() {
        $stmt = $this->conn->prepare("SELECT * FROM commands WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateCommand() {
        $stmt = $this->conn->prepare("UPDATE commands SET creation_date=?, user_id=? WHERE id = ?");
        $stmt->bind_param("sii", $this->creation_date, $this->user_id, $this->id);
        return $stmt->execute();
    }

    public function deleteCommand() {
        $stmt = $this->conn->prepare("DELETE FROM commands WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    public function getAllCommands() {
        $stmt = $this->conn->prepare("SELECT * FROM commands");
        $stmt->execute();
        $result = $stmt->get_result();
        $commands = array();

        while ($row = $result->fetch_assoc()) {
            $commands[] = $row;
        }

        return $commands;
    }

    public function validateInput($data) {
        // Add validation rules for input data
        $rules = array(
            'creation_date' => 'Creation date is required.',
            'user_id' => 'User ID is required.'
        );

        foreach ($rules as $field => $message) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return ErrorHandler::invalidInputError($message);
            }
        }

        return null; // No validation error
    }
}
