<?php
require_once "../Homework_10/models/Model.php";
require_once "../Homework_10/models/Product.php";
require_once "../Homework_10/Controllers/Product_Controller.php";

// Set environment variables
$env = parse_ini_file('../Homework_10/.env');

// Check if the environment variables exist
if (!$env || !isset($env['DBNAME'], $env['DBHOST'], $env['DBUSER'], $env['DBPASS'])) {
    die("Error: Missing one or more required database configuration values.");
}

define('DBNAME', $env['DBNAME']);
define('DBHOST', $env['DBHOST']);
define('DBUSER', $env['DBUSER']);
define('DBPASS', $env['DBPASS']);

// Attempt to establish a connection to the database using PDO
try {
    $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: Could not connect to the database. " . $e->getMessage());
}

use Homework_10\Controllers\ProductController;

// Get URI without query string
$uri = strtok($_SERVER["REQUEST_URI"], '?');
$uriArray = explode("/", $uri);

// Define the routes for the API and views

// GET: Single or filtered/all products
if ($uriArray[1] === 'api' && $uriArray[2] === 'products' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Only id if provided
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $productController = new ProductController();

    // Check if we have an ID for a single product or a query for filtering
    if ($id) {
        $productController->getProductByID($id);
    } else {
        // If query parameter 'name' exists, filter products by name
        $query = isset($_GET['name']) ? $_GET['name'] : null;
        if ($query) {
            $productController->getProducts($query);
        } else {
            $productController->getAllProducts();
        }
    }
}

// POST: Save product
if ($uriArray[1] === 'api' && $uriArray[2] === 'products' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $productController = new ProductController();
    $productController->saveProduct();
}

// PUT: Update product
if ($uriArray[1] === 'api' && $uriArray[2] === 'products' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $productController = new ProductController();
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $productController->updateProduct($id);
}

// DELETE: Delete product
if ($uriArray[1] === 'api' && $uriArray[2] === 'products' && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $productController = new ProductController();
    $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
    $productController->deleteProduct($id);
}

// Views for rendering HTML

// View for adding a new product
if ($uri === '/new-product' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $productController = new ProductController();
    $productController->productsAddView();
}

// View for updating a product
if ($uriArray[1] === 'products' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $productController = new ProductController();
    $productController->productsUpdateView();
}

// View for deleting a product
if ($uriArray[1] === 'delete-product' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $productController = new ProductController();
    $productController->productsDeleteView();
}

// Default view for displaying products
if ($uriArray[1] === '' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $productController = new ProductController();
    $productController->productsView();
}

// If no matching route, show 404
include '../public/assets/views/notFound.html';
exit();
?>
