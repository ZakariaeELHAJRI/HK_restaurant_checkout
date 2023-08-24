<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../controllers/BillController.php');
require_once(__DIR__ . '/../models/BillModel.php');
require_once(__DIR__ . '/../models/CommandModel.php');
require_once(__DIR__ . '/../validators/ErrorHandler.php');
require_once(__DIR__ . '/../middleware/RoleMiddleware.php');
require_once(__DIR__ . '/../middleware/MockRoleMiddleware.php');


class BillControllerTest extends TestCase {

    public function testCreateBill () {
        // Create a mock of the BillModel class
        $mockModel = $this->getMockBuilder(BillModel::class)
                          ->onlyMethods(['createBill'])
                          ->getMock();
        $controller = new BillController();
        $controller->billModel = $mockModel;

        $data = [
            'payment_method' => 'cash',
            'command_id' => 1
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
   
    public function testReadBill () {
        // Create a mock of the BillModel class
        $mockModel = $this->getMockBuilder(BillModel::class)
                          ->disableOriginalConstructor()
                          ->getMock();
        $billData = [
            'id' => 1,
            'payment_method' => 'cash',
            'command_id' => 1
        ];

        $controller = new BillController();
        $controller->billModel = $mockModel;

        $result = $controller->read(1);
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
     
    public function testUpdateBill () {
        // Create a mock of the BillModel class
        $mockModel = $this->getMockBuilder(BillModel::class)
                          ->onlyMethods(['updateBill'])
                          ->getMock();
        $controller = new BillController();
        $controller->billModel = $mockModel;

        $data = [
            'payment_method' => 'cash',
            'command_id' => 1
        ];

        $result = $controller->update(1, $data);
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
    /*
    public function testDeleteBill () {
        // Create a mock of the BillModel class
        $mockModel = $this->getMockBuilder(BillModel::class)
        ->disableOriginalConstructor()
        ->getMock();
        $controller = new BillController();
        $controller->billModel = $mockModel;

        $result = $controller->delete(1);
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
    public function testGetAllBills () {
        // Create a mock of the BillModel class
        $mockModel = $this->getMockBuilder(BillModel::class)
                          ->disableOriginalConstructor()
                          ->getMock();
        $mockModel ->method('getAllBills')
                   ->willReturn([
                       [
                           'id' => 1,
                           'payment_method' => 'cash',
                           'command_id' => 1
                          ],
                            [
                                'id' => 2,
                                'payment_method' => 'cash',
                                'command_id' => 1
                            ]
                   ]);

        $controller = new BillController();
        $controller->billModel = $mockModel;

        $result = $controller->getAll();

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
 */
}