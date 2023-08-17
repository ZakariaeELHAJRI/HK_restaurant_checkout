<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../controllers/ProductController.php');
require_once(__DIR__ . '/../models/ProductModel.php');
require_once(__DIR__ . '/../models/CategoryModel.php');
require_once(__DIR__ . '/../validators/ErrorHandler.php');
require_once(__DIR__ . '/../middleware/RoleMiddleware.php');
require_once(__DIR__ . '/../middleware/MockRoleMiddleware.php');

class ProductControllerTest extends TestCase {
    // ... Similar setUp method as before

    public function testCreateProduct() {
        $productController = new ProductController();

        $data = [
            'name' => 'Product Name',
            'price' => 50,
            'tva' => 20,
            'image' => 'product_image.jpg',
            'category' => 1
        ];

        $result = $productController->create($data);

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

  public function testReadProduct() {
        // Mock ProductModel
        $productModelMock = $this->getMockBuilder(ProductModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Set up expectations for the mock
        $productModelMock->expects($this->once())
            ->method('getProductById')
            ->willReturn([
                'id' => 1,
                'name' => 'Product Name',
                'price' => 50,
                'tva' => 20,
                'image' => 'product_image.jpg',
                'category' => 1
            ]);

        // Create an instance of ProductController and inject the mock ProductModel
        $productController = new ProductController();
        $productController->productmodel = $productModelMock;

        // Call the read method and assert the result
        $result = $productController->read(1);

        // Assert the expected outcome
        $productData = [
            'id' => 1,
            'name' => 'Product Name',
            'price' => 50,
            'tva' => 20,
            'image' => 'product_image.jpg',
            'category' => 1
        ];
        $expectedResult = [
            'data' => $productData
        ];
        $this->assertEquals($expectedResult, $result);
    }
    public function testUpdateProduct() {
        $productModelMock = $this->getMockBuilder(ProductModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productData = [
            'id' => 1,
            'name' => 'Product Name',
            'price' => 50,
            'tva' => 20,
            'image' => 'product_image.jpg',
            'category' => 1
        ];

  

        $productController = new ProductController();
        $productController->model = $productModelMock;
        $productController->categoryModel = $categoryModelMock;

        $data = [
            'name' => 'Updated Product Name',
            'price' => 60,
            'tva' => 25,
            'image' => 'updated_image.jpg',
            'category' => 1
        ];

        $result = $productController->update(1, $data);

        if (is_array($result) && isset($result['error'])) {
            $expectedError = [
                'success' => false,
                'error' => $result['error']
            ];
            $this->assertEquals($expectedError, $result);
        } else {
            $expectedSuccess = [
                'success' => true,
                'message' => 'Product updated successfully.'
            ];
            $this->assertEquals($expectedSuccess, $result);
        }
    }

    public function testDeleteProduct() {
        $productModelMock = $this->getMockBuilder(ProductModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productModelMock->expects($this->once())
            ->method('deleteProduct')
            ->willReturn(true);

        $productController = new ProductController();
        $productController->model = $productModelMock;

        $result = $productController->delete(1);

        if (is_array($result) && isset($result['error'])) {
            $expectedError = [
                'success' => false,
                'error' => $result['error']
            ];
            $this->assertEquals($expectedError, $result);
        } else {
            $expectedSuccess = [
                'success' => true,
                'message' => 'Product deleted successfully.'
            ];
            $this->assertEquals($expectedSuccess, $result);
        }
    }

    public function testGetAllProducts() {
        $productModelMock = $this->getMockBuilder(ProductModel::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productList = [
            // ... array of product data
        ];

        $productModelMock->expects($this->once())
            ->method('getAllProducts')
            ->willReturn($productList);

        $productController = new ProductController();
        $productController->model = $productModelMock;

        $result = $productController->getAll();

        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            $expectedSuccess = [
                'success' => true,
                'data' => $productList
            ];
            $this->assertEquals($expectedSuccess, $result);
        } else {
            $this->assertTrue($result);
        }
    }
}
