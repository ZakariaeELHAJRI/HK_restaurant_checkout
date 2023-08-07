<?php
require_once(__DIR__.'/../models/CommandModel.php');
require_once('BaseController.php');
require_once(__DIR__.'/../validators/ErrorHandler.php');

class CommandController extends BaseController {
    private $model;

    public function __construct() {
        $this->model = new CommandModel();
    }

    public function create($data) {
        $error = $this->model->validateInput($data);
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->setModelAttributes($data);

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

    public function read($id) {
        $error = $this->model->validateInput(array('id' => $id));
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
        $error = $this->model->validateInput($data);
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->model->setId($id);
        $this->setModelAttributes($data);

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

    public function delete($id) {
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
        // Get all commands from the database
        $commands = $this->model->getAllCommands();

        return array(
            "success" => true,
            "data" => $commands
        );
    }

    private function setModelAttributes($data) {
        $this->model->setCreationDate($data['creation_date']);
        $this->model->setUserId($data['user_id']);
    }
}
?>