<?php
require_once '../templates/header.php';
?>

<div class="bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-bold mb-4">Selamat Datang di Sistem Thrift</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-blue-100 p-6 rounded">
            <h2 class="text-xl font-bold mb-2">Total Produk</h2>
            <p class="text-3xl"><?= $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() ?></p>
        </div>
        <div class="bg-green-100 p-6 rounded">
            <h2 class="text-xl font-bold mb-2">Penjualan Hari Ini</h2>
            <p class="text-3xl">Rp <?= number_format($pdo->query("SELECT SUM(total_amount) FROM sales WHERE DATE(sale_date) = CURDATE()")->fetchColumn() ?: 0, 0, ',', '.') ?></p>
        </div>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>