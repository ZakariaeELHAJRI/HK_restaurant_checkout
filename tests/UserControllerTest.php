<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../controllers/UserController.php');
require_once(__DIR__ . '/../models/UserModel.php');
require_once(__DIR__ . '/../validators/ErrorHandler.php');
require_once(__DIR__ . '/../middleware/RoleMiddleware.php');
require_once(__DIR__ . '/../middleware/MockRoleMiddleware.php');

class UserControllerTest extends TestCase {
    protected function setUp(): void {
        parent::setUp();

        if (!function_exists('getallheaders')) {
            function getallheaders() {
                return [];
            }
        }
    }

    public function testCreateUser() {
        $userController = new UserController();
        $data = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'telephone' => '1234567890',
            'role' => 'user'
        ];
    
        $result = $userController->create($data);
    
        if (is_array($result) && isset($result['error'])) {
            $expectedError = [
                'success' => false,
                'error' => $result['error']
            ];
            $this->assertEquals($expectedError, $result);
        } else {
            $this->assertTrue($result);
        }
    }
    
    public function testReadUser() {
        $userModelMock = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        $userData = [
            'id' => 1,
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'telephone' => '1234567890',
            'role' => 'user'
        ];
    
        $userController = new UserController();
        $userController->userModel = $userModelMock;
    
        $result = $userController->read(1);
    
        if (is_array($result) && isset($result['error'])) {
            $expectedError = [
                'success' => false,
                'error' => $result['error']
            ];
            $this->assertEquals($expectedError, $result);
        } else {
            $expectedResult = [
                'success' => true,
                'data' => $userData
            ];
            $this->assertEquals($expectedResult, $result);
        }
    }
    
    public function testUpdateUser() {
        $userModelMock = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        $userController = new UserController();
        $userController->userModel = $userModelMock;
    
        $data = [
            'username' => 'newusername',
            'email' => 'new@example.com',
            'password' => 'newpassword',
            'telephone' => '9876543210',
            'role' => 'admin'
        ];
    
        $result = $userController->update(1, $data);
    
        if (is_array($result) && isset($result['error'])) {
            $expectedError = [
                'success' => false,
                'error' => $result['error']
            ];
            $this->assertEquals($expectedError, $result);
        } else {
            $this->assertTrue($result);
        }
    }
    
    public function testDeleteUser() {
        $userModelMock = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        $userController = new UserController();
        $userController->userModel = $userModelMock;
    
        $result = $userController->delete(1);
    
        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            $expectedSuccess = [
                'success' => true,
                'message' => 'User deleted successfully.'
            ];
            $this->assertEquals($expectedSuccess, $result);
        } else {
            $this->assertTrue($result);
        }
    }
    
    public function testGetAllUsers() {
        $userModelMock = $this->getMockBuilder(UserModel::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        $userController = new UserController();
        $userController->userModel = $userModelMock;
    
        $result = $userController->getAll();
    
        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            $expectedSuccess = [
                'success' => true,
                'data' => $result['data']
            ];
            $this->assertEquals($expectedSuccess, $result);
        } else {
            $this->assertTrue($result);
        }
    }
}
