<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

$sales = $pdo->query("
    SELECT s.*, c.name AS customer_name 
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.customer_id
    ORDER BY s.sale_date DESC
")->fetchAll();
?>

<h1 class="text-2xl font-bold mb-6">Daftar Penjualan</h1>

<div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left">No. Invoice</th>
                <th class="px-6 py-3 text-left">Tanggal</th>
                <th class="px-6 py-3 text-left">Pelanggan</th>
                <th class="px-6 py-3 text-left">Total</th>
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
                        <td class="px-6 py-3">
                            <a href="details.php?id=<?= $sale['sale_id'] ?>" class="text-blue-500 hover:underline">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center py-6 text-gray-500">Belum ada transaksi penjualan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../templates/footer.php'; ?>