<?php
require_once(__DIR__.'/../models/LigneCommandModel.php');
require_once('BaseController.php');
require_once(__DIR__.'/../validators/ErrorHandler.php');
require_once(__DIR__.'/../models/CommandModel.php');
require_once(__DIR__.'/../models/ProductModel.php');
require_once(__DIR__.'/../middleware/RoleMiddleware.php');

class LigneCommandController extends BaseController {
    private $model;

    public function __construct() {
        $this->model = new LigneCommandModel();
    }

    public function create($data) {
        //RoleMiddleware::authorizeRoles(array("gestionnaire" , "caissier"));
        $requiredFields = array(
            'quantity' => 'Ligne command quantity is required.',
            'id_command' => 'Ligne command id_command is required.',
            'product_id' => 'Ligne command product_id is required.'
        );
        $error = $this->model->validateInput($data , $requiredFields);
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->setModelAttributes($data);
        //check if the command exists in the database using the function checkCommandExist
        $errorCommand = $this->checkCommandExist($data['id_command']);
        if ($errorCommand !== null) {
            return ErrorHandler::badRequestError($errorCommand);
        }
        //check if the product exists in the database using the function checkProductExist
        $errorProduct = $this->checkProductExist($data['product_id']);
        if ($errorProduct !== null) {
            return ErrorHandler::badRequestError($errorProduct);
        }
        // Create the ligne command in the database
        if ($this->model->createLigneCommand()) {
            return array(
                "success" => true,
                "message" => "Ligne command created successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to create ligne command. ");
        }
    }

    public function read($id) {
        //RoleMiddleware::authorizeRoles(array("gestionnaire", "patron"));
        $requiredFields = array(
            'id' => 'Ligne command ID is required.'
        );
        $error = $this->model->validateInput(array('id' => $id) , $requiredFields);
        if ($error !== null) {
            return $error;
        }

        // Set attribute in the model
        $this->model->setId($id);

        // Get the ligne command from the database
        $ligne_command = $this->model->getLigneCommandById();

        if ($ligne_command) {
            return array(
                "success" => true,
                "data" => $ligne_command
            );
        } else {
            return ErrorHandler::notFoundError("Ligne command not found.");
        }
    }

    public function update($id, $data) {
        //RoleMiddleware::authorizeRoles(array("gestionnaire"));
        $requiredFields = array(
            'quantity' => 'Ligne command quantity is required.',
            'id_command' => 'Ligne command id_command is required.',
            'product_id' => 'Ligne command product_id is required.'
        );
        $validFields = array();
        $error = null;
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $requiredFields) && !empty($value)) {
                $validFields[$key] = $value;
            }
        }
        if (empty($validFields)) {
            $error = 'No valid fields provided for update.';
        }
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->model->setId($id);
        $this->setModelAttributes($data);
    // check ligne command exist in database 
        $ligne_command = $this->model->getLigneCommandById();
        if (!$ligne_command) {
            return ErrorHandler::badRequestError("Ligne command does not exist.");
        }
      //check if the command exists in the database using the function checkCommandExist
      $errorCommand = $this->checkCommandExist($data['id_command']);
      if ($errorCommand !== null) {
          return ErrorHandler::badRequestError($errorCommand);
      }
      //check if the product exists in the database using the function checkProductExist
      $errorProduct = $this->checkProductExist($data['product_id']);
      if ($errorProduct !== null) {
          return ErrorHandler::badRequestError($errorProduct);
      }

        // Update the ligne command in the database
        if ($this->model->updateLigneCommand()) {
            return array(
                "success" => true,
                "message" => "Ligne command updated successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to update ligne command.");
        }
    }

    public function delete($id) {
        //RoleMiddleware::authorizeRoles(array("gestionnaire"));
        $requiredFields = array(
            'id' => 'Ligne command ID is required.'
        );
        $error = $this->model->validateInput(array('id' => $id) , $requiredFields);
        if ($error !== null) {
            return $error;
        }

        // Set attribute in the model
        $this->model->setId($id);

        // Delete the ligne command from the database
        if ($this->model->deleteLigneCommand()) {
            return array(
                "success" => true,
                "message" => "Ligne command deleted successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to delete ligne command.");
        }
    }

    public function getAll() {
        //RoleMiddleware::authorizeRoles(array("gestionnaire", "patron"));
        // Get all ligne commands from the database
        $ligne_commands = $this->model->getAllLigneCommands();

        return array(
            "success" => true,
            "data" => $ligne_commands
        );
    }

    private function setModelAttributes($data) {
        $this->model->setQuantity($data['quantity']);
        $this->model->setLigneCommandId($data['id_command']);
        $this->model->setProductId($data['product_id']);
    }

    private function checkCommandExist($id_command) {
        $commandModel = new CommandModel();
        $commandModel->setId($id_command);
        $command = $commandModel->getCommandById();
        if (!$command) {
            return "Command does not exist."; // Return the error message directly
        }
    }
    
    private function checkProductExist($product_id) {
        $productModel = new ProductModel();
        $productModel->setId($product_id);
        $product = $productModel->getProductById();
        if (!$product) {
            return "Product does not exist."; // Return the error message directly
        }
    }
    
}
?>
