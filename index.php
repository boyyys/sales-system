<?php
include 'config/db.php';

// Query dengan error handling
try {
    $stmt = $pdo->query("SELECT 
        id,
        name,
        category,
        cost_price,
        price,
        stock,
        created_at 
        FROM products");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Product Management</h1>
            <div class="space-x-4">
                <a href="add_product.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Product</a>
                <a href="sales.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Sales Page</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Beli</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Jual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Ditambahkan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $row): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['id'] ?? '') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['name'] ?? '') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['category'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    $<?= isset($row['cost_price']) ? number_format((float)$row['cost_price'], 2) : '0.00' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    $<?= isset($row['price']) ? number_format((float)$row['price'], 2) : '0.00' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-green-600">
                                    $<?=
                                        isset($row['price'], $row['cost_price']) ?
                                            number_format((float)$row['price'] - (float)$row['cost_price'], 2) :
                                            '0.00'
                                        ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $row['stock'] ?? 0 ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= $row['created_at'] ?? '' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada produk yang tersedia
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>