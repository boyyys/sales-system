<?php
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Insert sales header
        $stmt = $pdo->prepare("INSERT INTO sales (customer_name, total_amount) VALUES (?, ?)");
        $stmt->execute([$_POST['customer_name'], 0]);
        $saleId = $pdo->lastInsertId();

        $totalAmount = 0;

        // Process each item
        foreach ($_POST['product_id'] as $key => $productId) {
            $quantity = $_POST['quantity'][$key];

            // Get product details
            $product = $pdo->query("SELECT price, stock FROM products WHERE id = $productId")->fetch();
            
            if ($product['stock'] < $quantity) {
                throw new Exception("Insufficient stock for product ID: $productId");
            }

            // Insert sale item
            $stmt = $pdo->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$saleId, $productId, $quantity, $product['price']]);

            // Update product stock
            $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$quantity, $productId]);

            $totalAmount += $product['price'] * $quantity;
        }

        // Update total amount
        $stmt = $pdo->prepare("UPDATE sales SET total_amount = ? WHERE id = ?");
        $stmt->execute([$totalAmount, $saleId]);

        $pdo->commit();
        header("Location: sales.php?success=1");
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: sales.php?error=" . urlencode($e->getMessage()));
    }
    exit;
}