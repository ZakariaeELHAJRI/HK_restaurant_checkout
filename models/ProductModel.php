<?php
require_once(__DIR__.'/../config/database.php');
require_once(__DIR__.'/../Validators/ErrorHandler.php');
class ProductModel {
    private $conn;
    private $id;
    private $name;
    private $price;
    private $tva;
    private $image;
    private $creation_date;
    private $category;

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

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setTva($tva) {
        $this->tva = $tva;
    }

    public function getTva() {
        return $this->tva;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getImage() {
        return $this->image;
    }

    public function setCreationDate($creation_date) {
        $this->creation_date = $creation_date;
    }

    public function getCreationDate() {
        return $this->creation_date;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function getCategory() {
        return $this->category;
    }

    public function createProduct() {
        $stmt = $this->conn->prepare("INSERT INTO products (name, price, tva, image, creation_date, category) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sddsss", $this->name, $this->price, $this->tva, $this->image, $this->creation_date, $this->category);
        
        if ($stmt->execute()) {
            return array(
                "success" => true,
                "message" => "Product created successfully."
            );
        } else {
            // Capture and log the specific database error message
            return ErrorHandler::serverError("Failed to create product. Database Error: " . $stmt->error);
        }
    }
    

    public function getProductById() {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        if ($product) {
            return array(
                "success" => true,
                "data" => $product
            );
        } else {
            return ErrorHandler::notFoundError("Product not found.");
        }
    }

    public function updateProduct() {
        $stmt = $this->conn->prepare("UPDATE products SET name=?, price=?, tva=?, image=?, creation_date=?, category=? WHERE id = ?");
        $stmt->bind_param("sddsssi", $this->name, $this->price, $this->tva, $this->image, $this->creation_date, $this->category, $this->id);
        if ($stmt->execute()) {
            return array(
                "success" => true,
                "message" => "Product updated successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to update product." . $stmt->error);
        }
    }

    public function deleteProduct() {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        if ($stmt->execute()) {
            return array(
                "success" => true,
                "message" => "Product deleted successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to delete product.");
        }
    }

    public function getAllProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM products");
        $stmt->execute();
        $result = $stmt->get_result();
        $products = array();

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return array(
            "success" => true,
            "data" => $products
        );
    }
}
?>