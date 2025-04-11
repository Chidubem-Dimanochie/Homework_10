<?php

namespace Homework_10\Controllers;

use Homework_10\Models\Product;

class ProductController
{
    // Method to validate product data
    public function validateProduct($inputData)
    {
        $errors = [];
        $name = $inputData['name'];
        $description = $inputData['description'];

        if ($name) {
            $name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
            if (strlen($name) < 2) {
                $errors['nameShort'] = 'Product name is too short';
            }
        } else {
            $errors['requiredName'] = 'Product name is required';
        }

        if ($description) {
            $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
            if (strlen($description) < 2) {
                $errors['descriptionShort'] = 'Description is too short';
            }
        } else {
            $errors['requiredDescription'] = 'Description is required';
        }

        if ($errors) {
            http_response_code(400);
            echo json_encode($errors);
            exit();
        }

        return [
            'name' => $name,
            'description' => $description,
        ];
    }
public function getProducts() {
    if (isset($_GET['name']) && !empty($_GET['name'])) {
        $name = $_GET['name'];

        // Prepare a SQL query to search for products by name
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE name LIKE :name");
            $stmt->execute([':name' => '%' . $name . '%']);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($products) {
                echo json_encode($products);
            } else {
                echo json_encode(['message' => 'No products found.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error while searching: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'No search query provided.']);
    }
    exit();
}
    public function getAllProducts()
    {
        $productModel = new Product();
        $products = $productModel->getAllProducts();

        header("Content-Type: application/json");
        echo json_encode($products);
        exit();
    }

    public function getProductByID($id)
    {
        $productModel = new Product();
        $product = $productModel->getProductById($id);

        header("Content-Type: application/json");
        echo json_encode($product);
        exit();
    }

    public function saveProduct()
    {
        $inputData = [
            'name' => $_POST['name'] ?: null,
            'description' => $_POST['description'] ?: null,
        ];
        $productData = $this->validateProduct($inputData);

        $product = new Product();
        $product->saveProduct([
            'name' => $productData['name'],
            'description' => $productData['description'],
        ]);

        http_response_code(200);
        echo json_encode(['success' => true]);
        exit();
    }

    public function updateProduct($id)
    {
        if (!$id) {
            http_response_code(404);
            exit();
        }

        parse_str(file_get_contents('php://input'), $_PUT);

        $inputData = [
            'name' => $_PUT['name'] ?: null,
            'description' => $_PUT['description'] ?: null,
        ];
        $productData = $this->validateProduct($inputData);

        $product = new Product();
        $product->updateProduct([
            'id' => $id,
            'name' => $productData['name'],
            'description' => $productData['description'],
        ]);

        http_response_code(200);
        echo json_encode(['success' => true]);
        exit();
    }

    public function deleteProduct($id)
    {
        if (!$id) {
            http_response_code(404);
            exit();
        }

        $product = new Product();
        $product->deleteProduct(['id' => $id]);

        http_response_code(200);
        echo json_encode(['success' => true]);
        exit();
    }

    public function productsView()
    {
        include '../Homework_10/View_Product.html';
        exit();
    }

    public function productsAddView()
    {
        include '../Homework_10/Add_Product.html';
        exit();
    }

    public function productsDeleteView()
    {
        include '../Homework_10/Delete_Product.html';
        exit();
    }

    public function productsUpdateView()
    {
        include '../Homework_10/Update_Product.html';
        exit();
    }
}
