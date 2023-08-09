<?php
require_once(__DIR__.'/../config/database.php');
require_once(__DIR__.'/../Validators/ErrorHandler.php');

class UserModel {
    private $conn;
    private $id;
    private $username;
    private $email;
    private $password;
    private $telephone;
    private $role;

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

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getRole() {
        return $this->role;
    }

    public function createUser() {
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, telephone, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $this->username, $this->email, $this->password, $this->telephone, $this->role);
        if ($stmt->execute()) {
            return array(
                "success" => true,
                "message" => "User created successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to create user.");
        }
    }

    public function getUserById() {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
        
    }
     //check if user exists by email
    public function getUserByEmail() {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    
    }
   

    public function updateUser() {
        $updateFields = array();
        $params = array();
    
        $fields = array(
            'username' => &$this->username,
            'email' => &$this->email,
            'password' => &$this->password,
            'telephone' => &$this->telephone,
            'role' => &$this->role
        );
    
        $types = '';
        foreach ($fields as $fieldName => &$fieldValue) {
            if (!empty($fieldValue)) {
                $updateFields[] = "$fieldName=?";
                $types .= 's';
                $params[] = &$fieldValue;
            }
        }
    
        if (empty($updateFields)) {
            return array(
                'success' => false,
                'message' => 'No valid fields provided for update.'
            );
        }
    
        $types .= 'i';
        $params[] = &$this->id;
    
        $updateQuery = 'UPDATE users SET ' . implode(', ', $updateFields) . ' WHERE id = ?';
    
        $stmt = $this->conn->prepare($updateQuery);
        call_user_func_array(array($stmt, 'bind_param'), array_merge(array($types), $params));
    
        if ($stmt->execute()) {
            return array(
                'success' => true,
                'message' => 'User updated successfully.'
            );
        } else {
            return ErrorHandler::serverError('Failed to update user.');
        }
    }
    

    public function deleteUser() {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        if ($stmt->execute()) {
            return array(
                "success" => true,
                "message" => "User deleted successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to delete user.");
        }
    }

    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = array();

        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return array(
            "success" => true,
            "data" => $users
        );
    }

    public function validateInput($data, $errorMessages) {
        foreach ($errorMessages as $field => $errorMessage) {
            if (!array_key_exists($field, $data) || empty($data[$field])) {
                return ErrorHandler::invalidInputError($errorMessage);
            }
        }
        return null; // 
    }
}
?>
