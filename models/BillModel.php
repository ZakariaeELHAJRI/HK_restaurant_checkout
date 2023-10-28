<?php
require_once(__DIR__.'/../config/database.php');

class BillModel {
    private $conn;
    private $id;
    private $creation_date;
    private $total_price_command;
    private $payment_method;
    private $command_id;

    
    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function calculateTotalPrice($command_id) {
        $query = "SELECT SUM(lc.quantity * (p.price * (1 + p.tva / 100))) AS total_price
                  FROM ligne_commands lc
                  INNER JOIN products  p ON lc.product_id = p.id
                  WHERE lc.id_command = ?";
    
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return 0; // Unable to prepare the statement
        }
        
        $stmt->bind_param("i", $command_id);
        $stmt->execute();
    
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total_price'];
        } else {
            return 0; // No records found or an error occurred
        }
        
        $stmt->close();
    }
    
    public function setId($id) {
        $this->id = $id;
    }

    public function setCreationDate($creation_date) {
        $this->creation_date = $creation_date;
    }

    public function setTotalPriceCommand($total_price_command) {
        $this->total_price_command = $total_price_command;
    }

    public function setPaymentMethod($payment_method) {
        $this->payment_method = $payment_method;
    }

    public function setCommandId($command_id) {
        $this->command_id = $command_id;
    }

    public function getId() {
        return $this->id;
    }

    public function getCreationDate() {
        return $this->creation_date;
    }

    public function getTotalPriceCommand() {
        return $this->total_price_command;
    }

    public function getPaymentMethod() {
        return $this->payment_method;
    }

    public function getCommandId() {
        return $this->command_id;
    }

    public function createBill() {
        $stmt = $this->conn->prepare("INSERT INTO bills (creation_date, total_price_command, payment_method, command_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sisi", $this->creation_date, $this->total_price_command, $this->payment_method, $this->command_id);
        return $stmt->execute();
    }

    public function getBillById() {
        $stmt = $this->conn->prepare("SELECT * FROM bills WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateBill() {
        $stmt = $this->conn->prepare("UPDATE bills SET creation_date=?, total_price_command=?, payment_method=?, command_id=? WHERE id = ?");
        $stmt->bind_param("siiii", $this->creation_date, $this->total_price_command, $this->payment_method, $this->command_id, $this->id);
        return $stmt->execute();
    }

    public function deleteBill() {
        $stmt = $this->conn->prepare("DELETE FROM bills WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    public function getAllBills() {
        $stmt = $this->conn->prepare("SELECT * FROM bills");
        $stmt->execute();
        $result = $stmt->get_result();
        $bills = array();

        while ($row = $result->fetch_assoc()) {
            $bills[] = $row;
        }

        return $bills;
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




