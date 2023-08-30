<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type:application/json');
require_once('BaseController.php');
require_once(__DIR__.'/../models/UserModel.php');
require_once(__DIR__.'/../vendor/autoload.php');
use Firebase\JWT\JWT;

class LoginController {

    public function authenticateUser($email, $password) {
        // Fetch user by email
        $userModel = new UserModel();
        $userModel->setEmail(htmlentities($email));
        $user = $userModel->getUserByEmail();
    
        if (!$user) {
            return array(
                "success" => false,
                "message" => "User not found."
            );
        }
    
        if (password_verify($password, $user['password'])) {
            // Generate JWT token
            $jwtPayload = array(
                "user_id" => $user['id'],
                "email" => $user['email'],
                "role" => $user['role']
            );
            $jwtToken = JWT::encode($jwtPayload, 'test123_test123', 'HS256');
            return array(
                "success" => true,
                "token" => $jwtToken ,
                "role" => $user['role']
            );
        } else {

            return array(
                "success" => false,
                "message" => "Invalid password."
            );
        }
    }
    
}
