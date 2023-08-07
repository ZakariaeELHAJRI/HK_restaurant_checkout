<?php
require_once(__DIR__.'/../models/BillModel.php');
require_once('BaseController.php');
require_once(__DIR__.'/../validators/ErrorHandler.php');
require_once(__DIR__.'/../models/CommandModel.php');


class BillController extends BaseController {
    private $model;

    public function __construct() {
        $this->model = new BillModel();
    }

    public function create($data) {
        $requiredFields = array(
            'payment_method' => 'Bill payment method is required.',
            'command_id' => 'Bill command id is required.'
        );
        $error = $this->model->validateInput($data , $requiredFields);
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->setModelAttributes($data);
        //check if the command exists in the database using the function checkCommand
        $errorCommand = $this->checkCommand($data['command_id']);
        if ($errorCommand !== null) {
            return ErrorHandler::badRequestError($errorCommand);
        }

             // Create the Bill in the database
        if ($this->model->createBill()) {
            return array(
                "success" => true,
                "message" => "Bill created successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to create Bill.");
        }
        
    }

    public function read($id) {
        $error = $this->model->validateInput(array('id' => $id));
        if ($error !== null) {
            return $error;
        }
        

        // Set attribute in the model
        $this->model->setId($id);
        if ($this->model->getBillById()) {
            return $this->model->getBillById();
        } else {
            return ErrorHandler::notFoundError("Bill does not exist.");
        }

    }

    public function update($id, $data) {
        $requiredFields = array(
            'payment_method' => 'Bill payment method is required.',
            'command_id' => 'Bill command id is required.'
        );
        $error = $this->model->validateInput($data , $requiredFields);
        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->setModelAttributes($data);

        // Set attribute in the model
        $this->model->setId($id);
        //check if the command exists in the database using the function checkCommand
        $errorCommand = $this->checkCommand($data['command_id']);
        if ($errorCommand !== null) {
            return ErrorHandler::badRequestError($errorCommand);
        }

        // Update the Bill in the database
        if ($this->model->updateBill()) {
            return array(
                "success" => true,
                "message" => "Bill updated successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to update Bill.");
        }
    }

    public function delete($id) {
        $error = $this->model->validateInput(array('id' => $id));
        if ($error !== null) {
            return $error;
        }

        // Set attribute in the model
        $this->model->setId($id);
        //check if the bill exists in the database
        $bill = $this->model->getBillById();
        if (!$bill) {
            return ErrorHandler::notFoundError("Bill does not exist.");
        }

        // Delete the Bill in the database
        if ($this->model->deleteBill()) {
            return array(
                "success" => true,
                "message" => "Bill deleted successfully."
            );
        } else {
            return ErrorHandler::serverError("Failed to delete Bill.");
        }
    }

    public function getAll() {
      $bills = $this->model->getAllBills();
        if ($bills) {
            return $bills;
        } else {
            return ErrorHandler::notFoundError("No bills found.");
        }
    }
   
    
    private function setModelAttributes($data) {
       if (isset($data['creation_date'])) {
            $this->model->setCreationDate($data['creation_date']);
        }
        else
        {
            $this->model->setCreationDate(date("Y-m-d H:i:s"));
        }
        // total price should be calculated automatically from the command using the function model->calculateTotalPrice
        $this->model->setPaymentMethod($data['payment_method']);
        $this->model->setCommandId($data['command_id']);
        $total_price_command = $this->model->calculateTotalPrice($data['command_id']);
        $this->model->setTotalPriceCommand($total_price_command);
    }
    // function check if the command exist
    public function checkCommand($id)
    {
        $commandModel = new CommandModel();
        $commandModel->setId($id);
        $command = $commandModel->getCommandById();
        if (!$command) {
            return ErrorHandler::badRequestError("Command does not exist.");
        }
        
    }
}
?>
