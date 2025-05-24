<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$product = $pdo->query("SELECT * FROM products WHERE product_id = $id")
    ->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    // ... (similar to create.php)
    
    $stmt = $pdo->prepare("UPDATE products SET 
        name = ?, 
        category = ?, 
        cost_price = ?, 
        sale_price = ?, 
        stock = ? 
        WHERE product_id = ?");
    $stmt->execute([$name, $category, $cost_price, $sale_price, $stock, $id]);
    
    header("Location: index.php?success=1");
}
?>

<!-- Form edit mirip dengan create.php -->