<?php
require_once(__DIR__.'/../models/CategoryModel.php');
require_once('BaseController.php');
require_once(__DIR__.'/../validators/ErrorHandler.php');

class CategoryController extends BaseController {
    private $model;

    public function __construct() {
        $this->model = new CategoryModel();
    }

    public function create($data) {
        $error = $this->model->validateInput($data);
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->setModelAttributes($data);

        // Create the category in the database
        if ($this->model->createCategory()) {
            return array(
                "success" => true,
                "message" => "Category created successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to create category.");
        }
    }

    public function read($id) {
        $error = $this->model->validateInput(array('id' => $id));
        if ($error !== null) {
            return $error;
        }

        // Set attribute in the model
        $this->model->setId($id);

        // Get the category from the database
        $category = $this->model->getCategoryById();

        if ($category) {
            return array(
                "success" => true,
                "data" => $category
            );
        } else {
            return ErrorHandler::notFoundError("Category not found.");
        }
    }

    public function update($id, $data) {
        $error = $this->model->validateInput($data);
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->model->setId($id);
        $this->setModelAttributes($data);

        // Update the category in the database
        if ($this->model->updateCategory()) {
            return array(
                "success" => true,
                "message" => "Category updated successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to update category.");
        }
    }

    public function delete($id) {
        $error = $this->model->validateInput(array('id' => $id));
        if ($error !== null) {
            return $error;
        }

        // Set attribute in the model
        $this->model->setId($id);

        // Delete the category from the database
        if ($this->model->deleteCategory()) {
            return array(
                "success" => true,
                "message" => "Category deleted successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to delete category.");
        }
    }

    public function getAll() {
        // Get all categories from the database
        $categories = $this->model->getAllCategories();

        return array(
            "success" => true,
            "data" => $categories
        );
    }

    private function setModelAttributes($data) {
        $this->model->setName($data['name']);
    }
}
?>
