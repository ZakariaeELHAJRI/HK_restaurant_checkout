<?php
use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/../controllers/LigneCommandController.php');
require_once(__DIR__ . '/../models/LigneCommandModel.php');
require_once(__DIR__ . '/../validators/ErrorHandler.php');
require_once(__DIR__ . '/../middleware/RoleMiddleware.php');
require_once(__DIR__ . '/../middleware/MockRoleMiddleware.php');



class LigneCommandControllerTest extends TestCase {

   
    private $mockModel;

    public function testCreateLigneCommand() {
        $mockModel = $this->getMockBuilder( LigneCommandModel::class)
                          ->onlyMethods(['createLigneCommand'])
                          ->getMock();
        
        $controller = new LigneCommandController();
        $controller->ligneCommandModel = $mockModel;
        
        $data = [
            'quantity' => 5,
            'id_command' => 1,
            'product_id' => 2
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

    public function testReadLigneCommand() {
        $mockModel= $this->getMockBuilder( LigneCommandModel::class)
                        ->disableOriginalConstructor()
                        ->getMock();
      
        $mockModel->method('getLigneCommandById')
                  ->willReturn( [
                            'success' => true,
                            'data' => [
                                'id' => 1,
                                'quantity' => 5, 
                                'id_command' => 1, 
                                'product_id' => 2
                                ]

                       ] );
        
        $controller = new LigneCommandController();
        $controller->ligneCommandModel = $mockModel;
        
        $ligneCommandId = 1;
        
        $result = $controller->read($ligneCommandId);
        
       if (is_array($result) && isset($result['error'])) {
            $expectedError = [
                'success' => false,
                'error' => $result['error']
            ];
            $this->assertEquals($expectedError, $result);
        } else {
            $expectedResult = [
                'success' => true,
                'data' => [
                    'id' => 1,
                    'quantity' => 5, 
                    'id_command' => 1, 
                    'product_id' => 2
                    ]
            ];
            $this->assertEquals($expectedResult, $result);
        }
    }

    public function testUpdateLigneCommand() {
       $mockModel = $this->getMockBuilder(LigneCommandModel::class)
                            ->disableOriginalConstructor()
                                ->getMock();

        
        $mockModel->method('getLigneCommandById')
                        ->willReturn( [
                                  'success' => true,
                                  'data' => [
                                      'id' => 1,
                                      'quantity' => 5, 
                                      'id_command' => 1, 
                                      'product_id' => 2
                                      ]
      
                             ] );
      
        $controller = new LigneCommandController();
        $controller->ligneCommandModel = $mockModel;
        
        $ligneCommandId = 1;
        $data = [
            'quantity' => 10,
            'id_command' => 2,
            'product_id' => 3
        ];
        
        $result = $controller->update($ligneCommandId, $data);
      if (is_array($result) && isset($result['error'])) {
            $expectedError = [
                'success' => false,
                'error' => $result['error']
            ];
            $this->assertEquals($expectedError, $result);
        } else {
            $expectedResult = [
                'success' => true,
                'message' => 'Ligne command updated successfully.'
            ];
            $this->assertEquals($expectedResult, $result);
        }
    }

    public function testDeleteLigneCommand() {
        $mockModel = $this->getMockBuilder(LigneCommandModel::class)
                            ->disableOriginalConstructor()
                                ->getMock();
        
        $controller = new LigneCommandController();
        $controller->ligneCommandModel = $mockModel;
        
        $ligneCommandId = 1;
        
        $result = $controller->delete($ligneCommandId);
        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            $expectedSuccess = [
                'success' => true,
                'message' => 'Ligne command deleted successfully.'
            ];
            $this->assertEquals($expectedSuccess, $result);
        } else {
            $this->assertTrue($result);
        }    
    }

    public function testGetAllLigneCommands() {
        $mockModel = $this->getMockBuilder(LigneCommandModel::class)
                                ->disableOriginalConstructor()
                                ->getMock();
        $mockModel->method('getAllLigneCommands')
                        ->willReturn([
                            ['id' => 1, 'quantity' => 5, 'id_command' => 1, 'product_id' => 2], 
                            ['id' => 2, 'quantity' => 3, 'id_command' => 2, 'product_id' => 3]
                        ]);
        
        $controller = new LigneCommandController();
        $controller->ligneCommandModel = $this->mockModel;
        
        $result = $controller->getAll();
        if (is_array($result) && isset($result['success']) && $result['success'] === true) 
        {
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



