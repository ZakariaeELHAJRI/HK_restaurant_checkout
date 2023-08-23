<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../controllers/CategoryController.php');
require_once(__DIR__ . '/../models/CategoryModel.php');
require_once(__DIR__ . '/../validators/ErrorHandler.php');
require_once(__DIR__ . '/../middleware/RoleMiddleware.php');
require_once(__DIR__ . '/../middleware/MockRoleMiddleware.php');


class CategoryControllerTest extends TestCase {

    public function testCreateCategory () {
        // Create a mock of the CategoryModel class
        $mockModel = $this->getMockBuilder(CategoryModel::class)
                          ->onlyMethods(['createCategory'])
                          ->getMock();
        $controller = new CategoryController();
        $controller->categoryModel = $mockModel;

        $data = [
            'name' => 'Sample Category'
        ];

        $result = $controller->create($data);
        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            $this->assertTrue($result['success']);
        } else {
            $this->assertEquals($result, ErrorHandler::serverError("Failed to create category."));
        }
    }
    public function testReadCategory () {
        // Create a mock of the CategoryModel class
        $mockModel = $this->getMockBuilder(CategoryModel::class)
                          ->disableOriginalConstructor()
                          ->getMock();
        $categoryData = [
            'id' => 1,
            'name' => 'Sample Category'
        ];

        $controller = new CategoryController();
        $controller->categoryModel = $mockModel;

        $result = $controller->read(1);
        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            $this->assertEquals($result['data'], $categoryData);
        } else {
            $this->assertEquals($result, ErrorHandler::serverError("Failed to get category."));
        }
    }

}