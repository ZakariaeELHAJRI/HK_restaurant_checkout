<?php
require_once(__DIR__.'/../vendor/autoload.php');
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class RoleMiddleware {
    public static function authorizeRoles($allowedRoles) {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            // Extract the JWT token from the Authorization header
            $authHeader = $headers['Authorization'];
            $jwtToken = str_replace('Bearer ', '', $authHeader);
            
            $key = 'test123_test123';
            $algorithm = array('HS256');
            $options = array('alg' => 'HS256');
            
            try {
                $decodedToken = JWT::decode($jwtToken, new Key($key, 'HS256'));
                $userRole = $decodedToken->role;

                if (!in_array($userRole, $allowedRoles)) {
                    // User doesn't have the required role, return a forbidden response
                    http_response_code(403);
                    echo json_encode(array("message" => "Access denied. You don't have permission for this action."));
                    exit;
                }
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(array("message" => "Invalid token. You must be logged in to perform this action."));
                exit;
            }
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Access denied. You must be logged in to perform this action."));
            exit;
        }
    }
}




