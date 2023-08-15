<?php
require_once(__DIR__.'/../models/CommandModel.php');
require_once(__DIR__.'/../models/UserModel.php');
require_once('BaseController.php');
require_once(__DIR__.'/../validators/ErrorHandler.php');
require_once(__DIR__.'/../middleware/RoleMiddleware.php');

class CommandController extends BaseController {
    private $model;

    public function __construct() {
        $this->model = new CommandModel();
    }

    public function create($data) {
        RoleMiddleware::authorizeRoles(array("gestionnaire" , "caissier"));
        $errorMessages = array(
            'user_id' => 'User ID is required.'
        );
        $error = $this->model->validateInput($data , $errorMessages);
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->setModelAttributes($data);
        // check if the user exists in the database
        $userModel = new UserModel();
        $userModel->setId($data['user_id']);
        $user = $userModel->getUserById();
        if (!$user) {
            return ErrorHandler::badRequestError("User does not exist.");
        }
        else
        {
              // Create the command in the database
        if ($this->model->createCommand()) {
            return array(
                "success" => true,
                "message" => "Command created successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to create command.");
        }
        }
      
    }

    public function read($id) {
        RoleMiddleware::authorizeRoles(array("gestionnaire", "patron"));
        $rules = array(
            'id' => 'Command ID is required.'
        );
       $error = $this->model->validateInput(array('id' => $id), $rules);
        if ($error !== null) {
            return $error;
        }

        // Set attribute in the model
        $this->model->setId($id);

        // Get the command from the database
        $command = $this->model->getCommandById();

        if ($command) {
            return array(
                "success" => true,
                "data" => $command
            );
        } else {
            return ErrorHandler::notFoundError("Command not found.");
        }
    }

    public function update($id, $data) {
        RoleMiddleware::authorizeRoles(array("gestionnaire"));
        $rules = array(
            'user_id' => 'User ID is required.'
        );
        $error = $this->model->validateInput($data , $rules);
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->model->setId($id);
        $this->setModelAttributes($data);
       // check if command exist in database 
        $command = $this->model->getCommandById();
        if (!$command) {
            return ErrorHandler::badRequestError("Command does not exist.");
        }
        // check if the user exists in the database
        $userModel = new UserModel();
        $userModel->setId($data['user_id']);
        $user = $userModel->getUserById();
        if (!$user) {
            return ErrorHandler::badRequestError("User does not exist.");
        }
        else
        {
                 // Update the command in the database
        if ($this->model->updateCommand()) {
            return array(
                "success" => true,
                "message" => "Command updated successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to update command.");
        }
        }
   
      
    }

    public function delete($id) {
        RoleMiddleware::authorizeRoles(array("gestionnaire"));
        $error = $this->model->validateInput(array('id' => $id));
        if ($error !== null) {
            return $error;
        }

        // Set attribute in the model
        $this->model->setId($id);

        // Delete the command from the database
        if ($this->model->deleteCommand()) {
            return array(
                "success" => true,
                "message" => "Command deleted successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to delete command.");
        }
    }

    public function getAll() {
        RoleMiddleware::authorizeRoles(array("gestionnaire", "patron"));
        // Get all commands from the database
        $commands = $this->model->getAllCommands();

        return array(
            "success" => true,
            "data" => $commands
        );
    }

    private function setModelAttributes($data) {
        if (isset($data['creation_date'])) {
            $this->model->setCreationDate($data['creation_date']);
        } else {
            $this->model->setCreationDate(date('Y-m-d H:i:s'));
        }
        $this->model->setUserId($data['user_id']);
    }
}
?>
