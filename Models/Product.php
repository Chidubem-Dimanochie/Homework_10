<?php

namespace Homework_10\Models;

class Product extends Model
{
    public function getAllProducts()
    {
        $query = "SELECT * FROM products";
        return $this->fetchAll($query);
    }

    public function getProductById($id)
    {
        $query = "SELECT * FROM products WHERE id = :id";
        return $this->fetchAllWithParams($query, ['id' => $id]);
    }

    public function saveProduct($productData)
    {
        $query = "INSERT INTO products (name, description) VALUES (:name, :description)";
        $this->query($query, $productData);
    }

    public function updateProduct($productData)
    {
        $query = "UPDATE products SET name = :name, description = :description WHERE id = :id";
        $this->query($query, $productData);
    }

    public function deleteProduct($productData)
    {
        $query = "DELETE FROM products WHERE id = :id";
        $this->query($query, $productData);
    }
}
