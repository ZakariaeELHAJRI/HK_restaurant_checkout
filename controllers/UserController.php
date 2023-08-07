<?php
require_once(__DIR__.'/../models/UserModel.php');
require_once('BaseController.php');
require_once(__DIR__.'/../validators/ErrorHandler.php');

class UserController extends BaseController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function create($data) {
        $errorMessages = array(
            'username' => 'Username is required.',
            'email' => 'Valid email is required.',
            'password' => 'Password is required.',
            'telephone' => 'Telephone is required.',
            'role' => 'Role is required.'
        );

        $error = $this->model->validateInput($data, $errorMessages);

        if ($error !== null) {
            return $error;
        }

        // Set attributes in the model
        $this->setModelAttributes($data);
        //before creating the user, check if the user already exists in the database by email
        $user = $this->model->getUserByEmail();
        if ($user) {
            return ErrorHandler::badRequestError("User already exists.");
        }
        // Create the user in the database
        return $this->model->createUser();
    }

    public function read($id) {
        $rules = array(
            'id' => 'User ID is required.'
        );

        $error =$this->model->validateInput( array('id' => $id),  $rules);

        if ($error !== null) {
            return $error;
        }

        // Set attribute in the model
        $this->model->setId($id);

        // Get the user from the database
        $user = $this->model->getUserById();
        if ($user) {
            return array(
                "success" => true,
                "data" => $user
            );
        } else {
            return ErrorHandler::notFoundError("User not found.");
        }
    }

    public function update($id, $data) {
        $rules = array(
            'username' => 'Username is required.',
            'email' => 'Valid email is required.',
            'password' => 'Password is required.',
            'telephone' => 'Telephone is required.',
            'role' => 'Role is required.'
        );
        $validFields = array();
        $error = null;
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $rules) && !empty($value)) {
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
        $this->setModelAttributes($validFields);
        //check user if exist by id 
        $user = $this->model->getUserById();
        if ($user) {
            $this->model->updateUser();
            return array(
                "success" => true,
                "message" => "User updated successfully."
            );
        } else {
            return ErrorHandler::notFoundError("User not found.");
        }
       
    }
    

    public function delete($id) {
        $rules = array(
            'id' => 'User ID is required.'
        );

        $error = $this->model->validateInput(
            array('id' => $id),
            $rules
        );

        if ($error !== null) {
            return $error;
        }

        // Set attribute in the model
        $this->model->setId($id);

        // Delete the user from the database
        return $this->model->deleteUser();
    }

    public function getAll() {
        // Get all users from the database
        return $this->model->getAllUsers();
    }

    private function setModelAttributes($data) {
        $fields = ['username', 'email', 'password', 'telephone', 'role'];
    
        foreach ($fields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $setterMethod = 'set' . ucfirst($field);
                $this->model->$setterMethod($data[$field]);
            }
        }
    }
   

}
?>
