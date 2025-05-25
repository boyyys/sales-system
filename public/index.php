<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /thrift-system/public/auth/login.php');
    exit;
}

require_once '../templates/header.php';

$totalProduk = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalTransaksi = $pdo->query("SELECT COUNT(*) FROM sales")->fetchColumn();
$totalPenjualan = $pdo->query("SELECT SUM(total_amount) FROM sales")->fetchColumn() ?: 0;
$totalProfit = $pdo->query("SELECT SUM(total_profit) FROM sales")->fetchColumn() ?: 0;
$penjualanHariIni = $pdo->query("SELECT SUM(total_amount) FROM sales WHERE DATE(sale_date) = CURDATE()")->fetchColumn() ?: 0;
$jumlahItemTerjual = $pdo->query("SELECT SUM(quantity) FROM sale_items")->fetchColumn() ?: 0;
?>

<div class="bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-bold mb-6">Dashboard Penjualan Thrift</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Kartu statistik -->
        <div class="bg-blue-100 p-6 rounded">
            <h2 class="text-xl font-semibold">Total Produk</h2>
            <p class="text-3xl font-bold"><?= $totalProduk; ?></p>
        </div>
        <div class="bg-green-100 p-6 rounded">
            <h2 class="text-xl font-semibold">Total Transaksi</h2>
            <p class="text-3xl font-bold"><?= $totalTransaksi; ?></p>
        </div>
        <div class="bg-yellow-100 p-6 rounded">
            <h2 class="text-xl font-semibold">Item Terjual</h2>
            <p class="text-3xl font-bold"><?= $jumlahItemTerjual ?: 0; ?></p>
        </div>
        <div class="bg-indigo-100 p-6 rounded">
            <h2 class="text-xl font-semibold">Total Penjualan</h2>
            <p class="text-3xl font-bold">Rp <?= number_format($totalPenjualan, 0, ',', '.'); ?></p>
        </div>
        <div class="bg-pink-100 p-6 rounded">
            <h2 class="text-xl font-semibold">Total Profit</h2>
            <p class="text-3xl font-bold">Rp <?= number_format($totalProfit, 0, ',', '.'); ?></p>
        </div>
        <div class="bg-green-200 p-6 rounded">
            <h2 class="text-xl font-semibold">Penjualan Hari Ini</h2>
            <p class="text-3xl font-bold">Rp <?= number_format($penjualanHariIni, 0, ',', '.'); ?></p>
        </div>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>