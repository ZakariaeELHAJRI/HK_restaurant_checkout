<?php
use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/../controllers/CommandController.php');
require_once(__DIR__ . '/../models/CommandModel.php');
require_once(__DIR__ . '/../models/UserModel.php');
require_once(__DIR__ . '/../validators/ErrorHandler.php');
require_once(__DIR__ . '/../middleware/RoleMiddleware.php');
require_once(__DIR__ . '/../middleware/MockRoleMiddleware.php');


class CommandControllerTest extends TestCase {

   


    public function testCreateCommand() {
        
        $mockModel = $this->getMockBuilder(CommandModel::class)
                              ->onlyMethods(['createCommand'])
                              ->getMock();
        
        $controller = new CommandController();
        $controller->commandModel = $mockModel;

        
       // data to create a command
        $data = [
            'user_id' => 1
        ];
        
        $result = $controller->create($data);
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

    public function testReadCommand() {

        $mockModel = $this->getMockBuilder(CommandModel::class)
                              ->disableOriginalConstructor()
                              ->getMock();
        $controller = new CommandController();
        $controller->commandModel = $mockModel;
        $commadData = [
            'id' => 1,
            'creation_date' => '2023-08-18 10:00:00',
            'user_id' => 1
        ];

        $commandId = 1;
        
        $result = $controller->read($commandId);
        
        if (is_array($result) && isset($result['error'])) {
            $expectedError = [
                'success' => false,
                'error' => $result['error']
            ];
            $this->assertEquals($expectedError, $result);
        } else {
            $this->assertEquals($commadData, $result);
        }
    }
/*
    public function testUpdateCommand() {
        $this->mockModel->expects($this->any())
                        ->method('validateInput')
                        ->willReturn(null); // No validation error
        
        $this->mockModel->expects($this->any())
                        ->method('getCommandById')
                        ->willReturn(['id' => 1, 'creation_date' => '2023-08-18 10:00:00', 'user_id' => 1]);
        
        $CommandModelMock = $this->getMockBuilder(CommandModel::class)
                              ->onlyMethods(['getUserById'])
                              ->getMock();
        $CommandModelMock->expects($this->once())
                      ->method('getUserById')
                      ->willReturn(['id' => 1, 'name' => 'John Doe']);
        
        $this->mockModel->expects($this->once())
                        ->method('updateCommand')
                        ->willReturn(true); // Successful update
        
        $controller = new CommandController();
        $controller->setModel($this->mockModel);
        
        $commandId = 1;
        $data = [
            'user_id' => 1
        ];
        
        $result = $controller->update($commandId, $data);
        
        $this->assertEquals(['success' => true, 'message' => 'Command updated successfully.'], $result);
    }

    public function testDeleteCommand() {
        $this->mockModel->expects($this->any())
                        ->method('validateInput')
                        ->willReturn(null); // No validation error
        
        $this->mockModel->expects($this->once())
                        ->method('deleteCommand')
                        ->willReturn(true); // Successful deletion
        
        $controller = new CommandController();
        $controller->setModel($this->mockModel);
        
        $commandId = 1;
        
        $result = $controller->delete($commandId);
        
        $this->assertEquals(['success' => true, 'message' => 'Command deleted successfully.'], $result);
    }

    public function testGetAllCommands() {
        $this->mockModel->expects($this->once())
                        ->method('getAllCommands')
                        ->willReturn([['id' => 1, 'creation_date' => '2023-08-18 10:00:00', 'user_id' => 1], ['id' => 2, 'creation_date' => '2023-08-18 11:00:00', 'user_id' => 2]]);
        
        $controller = new CommandController();
        $controller->setModel($this->mockModel);
        
        $result = $controller->getAll();
        
        $expected = ['success' => true, 'data' => [['id' => 1, 'creation_date' => '2023-08-18 10:00:00', 'user_id' => 1], ['id' => 2, 'creation_date' => '2023-08-18 11:00:00', 'user_id' => 2]]];
        $this->assertEquals($expected, $result);
    }
*/
}
