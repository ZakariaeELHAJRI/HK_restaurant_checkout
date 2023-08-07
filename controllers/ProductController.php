<?php
require_once(__DIR__.'/../models/ProductModel.php');
require_once('BaseController.php');
require_once(__DIR__.'/../models/CategoryModel.php');

class ProductController extends BaseController {
    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    public function create($data) {
        $requiredFields = array(
            'name' => 'Product name is required.',
            'price' => 'Product price is required.',
            'tva' => 'Product TVA is required.',
            'image' => 'Product image is required.',
            'category' => 'Product category is required.'
        );

        $error = $this->validateInput($data, $requiredFields);

        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->setModelAttributes($data);
        //check if the category exists in the database
        $categoryModel = new CategoryModel();
        $categoryModel->setId($data['category']);
        $category = $categoryModel->getCategoryById();
        if (!$category) {
            return ErrorHandler::badRequestError("Category does not exist.");
        }
        // Create the product in the database
        return $this->model->createProduct();
    }

    public function read($id) {
        $requiredFields = array(
            'id' => 'Product ID is required.'
        );
        $error = $this->validateInput(array('id' => $id), $requiredFields);
        if ($error !== null) {
            return $error;
        }
        // Set attribute in the model
        $this->model->setId($id);

        return $this->model->getProductById();
    }

    public function update($id, $data) {
        $requiredFields = array(
            'name' => 'Product name is required.',
            'price' => 'Product price is required.',
            'tva' => 'Product TVA is required.',
            'image' => 'Product image is required.',
            'category' => 'Product category is required.'
        );
        $error = $this->validateInput($data, $requiredFields);
        if ($error !== null) {
            return $error;
        }
        // Set attributes in the model
        $this->model->setId($id);
        //check if the product exists in the database
        $this->setModelAttributes($data);
      
        return var_dump($this->model);
       
    }

    public function delete($id) {
        $requiredFields = array(
            'id' => 'Product ID is required.'
        );
        $error = $this->validateInput(array('id' => $id), $requiredFields);
        if ($error !== null) {
            return $error;
        }
        // Set attribute in the model
        $this->model->setId($id);

        return $this->model->deleteProduct();
    }

    public function getAll() {
        return $this->model->getAllProducts();
    }

    private function setModelAttributes($data) {
        $this->model->setName($data['name']);
        $this->model->setPrice($data['price']);
        $this->model->setTva($data['tva']);
        $this->model->setImage($data['image']);
        // set default creation date if not provided
        if (isset($data['creation_date'])) {
            $this->model->setCreationDate($data['creation_date']);
        } else {
            $this->model->setCreationDate(date('Y-m-d H:i:s'));
        }
        $this->model->setCategory($data['category']);
    }
    private function validateInput($data, $requiredFields) {
        $error = null;

        foreach ($requiredFields as $key => $value) {
            if (!isset($data[$key])) {
                $error = array(
                    'message' => $value
                );
                break;
            }
        }

        return $error;
    }
}
?>
