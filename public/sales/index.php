<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

// Ambil data penjualan + total barang terjual
$sales = $pdo->query("
    SELECT s.*, 
           c.name AS customer_name,
           (
               SELECT SUM(si.quantity) 
               FROM sale_items si 
               WHERE si.sale_id = s.sale_id
           ) AS total_items
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.customer_id
    ORDER BY s.sale_date DESC
")->fetchAll();
?>

<h1 class="text-2xl font-bold mb-6">Daftar Penjualan</h1>

<!-- Tombol Tambah Penjualan -->
<a href="create.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-600">
    + Tambah Penjualan
</a>

<!-- Tabel Penjualan -->
<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left">No. Invoice</th>
                <th class="px-6 py-3 text-left">Tanggal</th>
                <th class="px-6 py-3 text-left">Pelanggan</th>
                <th class="px-6 py-3 text-left">Total</th>
                <th class="px-6 py-3 text-left">Barang Terjual</th>
                <th class="px-6 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($sales) > 0): ?>
                <?php foreach ($sales as $sale): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3"><?= htmlspecialchars($sale['invoice_number']) ?></td>
                        <td class="px-6 py-3"><?= date('d/m/Y H:i', strtotime($sale['sale_date'])) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($sale['customer_name'] ?? 'Umum / Walk-in') ?></td>
                        <td class="px-6 py-3">Rp <?= number_format($sale['total_amount'], 0, ',', '.') ?></td>
                        <td class="px-6 py-3"><?= $sale['total_items'] ?? 0 ?> pcs</td>
                        <td class="px-6 py-3">
                            <a href="details.php?id=<?= $sale['sale_id'] ?>" class="text-blue-500 hover:underline">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">Belum ada transaksi penjualan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../templates/footer.php'; ?>