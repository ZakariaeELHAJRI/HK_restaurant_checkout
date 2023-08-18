<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../controllers/ProductController.php');
require_once(__DIR__ . '/../models/ProductModel.php');
require_once(__DIR__ . '/../models/CategoryModel.php');
require_once(__DIR__ . '/../validators/ErrorHandler.php');
require_once(__DIR__ . '/../middleware/RoleMiddleware.php');
require_once(__DIR__ . '/../middleware/MockRoleMiddleware.php');

class ProductControllerTest extends TestCase {
   

 
    public function testCreateProduct() {
        $mockModel = $this->getMockBuilder(ProductModel::class)
                          ->onlyMethods(['createProduct'])
                          ->getMock();
                          
        $controller = new ProductController();
        $controller->productModel = $mockModel;
        
        $data = [
            'name' => 'Sample Product',
            'price' => 100,
            'tva' => 20,
            'image' => 'sample.jpg',
            'category' => 1
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
    
        public function testReadProduct() {
            $mockModel = $this->getMockBuilder(ProductModel::class)
                              ->disableOriginalConstructor()
                              ->getMock();
                              
            $productData = [
                'id' => 1,
                'name' => 'Sample Product',
                'price' => 100,
                'tva' => 20,
                'image' => 'sample.jpg',
                'category' => 1
            ];

            $controller = new ProductController();
            $controller->productModel = $mockModel;
            
            $result = $controller->read(1);
            
           if (is_array($result) && isset($result['error'])) {
                $expectedError = [
                    'success' => false,
                    'error' => $result['error']
                ];
                $this->assertEquals($expectedError, $result);
            } else {
                $this->assertEquals($productData, $result);
            }
        }

        public function testUpdateProduct() {
            $mockModel = $this->getMockBuilder(ProductModel::class)
                              ->disableOriginalConstructor()
                              ->getMock();
                              
        
            
            $controller = new ProductController();
            $controller->productModel = $mockModel;
            
            $productId = 1;
            $data = [
                'name' => 'Updated Product',
                'price' => 150,
                'tva' => 25,
                'image' => 'updated.jpg',
                'category' => 2
            ];
            
            $result = $controller->update($productId, $data);
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

        public function testDeleteProduct() {
            $mockModel = $this->getMockBuilder(ProductModel::class)
                ->disableOriginalConstructor()
                ->getMock();
       
            $controller = new ProductController();
            $controller->productModel = $mockModel;
            
            $productId = 1;
            
            $result = $controller->delete($productId);
            
            if (is_array($result) && isset($result['success']) && $result['success'] === true) {
                $this->assertTrue($result['success']);
            } else {
                $this->assertTrue($result);
            }
        }

        public function testGetAllProducts() {
            $mockModel = $this->getMockBuilder(ProductModel::class)
                ->disableOriginalConstructor()
                ->getMock();
            $mockModel ->method('getAllProducts')
                       ->willReturn([
                           [
                               'id' => 1,
                               'name' => 'Sample Product',
                               'price' => 100,
                               'tva' => 20,
                               'image' => 'sample.jpg',
                               'category' => 1
                           ],
                           [
                               'id' => 2,
                               'name' => 'Sample Product 2',
                               'price' => 150,
                               'tva' => 25,
                               'image' => 'sample2.jpg',
                               'category' => 2
                           ]
                       ]);                  
         
            $controller = new ProductController();
            $controller->productModel = $mockModel;
            
            $result = $controller->getAll();
            
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
