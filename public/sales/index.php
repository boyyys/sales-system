<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

$sales = $pdo->query("
    SELECT s.*, c.name AS customer_name 
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.customer_id
    ORDER BY sale_date DESC
")->fetchAll();
?>

<h1 class="text-2xl font-bold mb-4">Daftar Penjualan</h1>

<table class="min-w-full bg-white rounded shadow">
    <thead>
        <tr>
            <th class="px-6 py-3">No. Invoice</th>
            <th class="px-6 py-3">Tanggal</th>
            <th class="px-6 py-3">Pelanggan</th>
            <th class="px-6 py-3">Total</th>
            <th class="px-6 py-3">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sales as $sale): ?>
            <tr class="border-b">
                <td class="px-6 py-4"><?= $sale['invoice_number'] ?></td>
                <td class="px-6 py-4"><?= date('d/m/Y', strtotime($sale['sale_date'])) ?></td>
                <td class="px-6 py-4"><?= $sale['customer_name'] ?? 'Walk-in' ?></td>
                <td class="px-6 py-4">Rp <?= number_format($sale['total_amount'], 0, ',', '.') ?></td>
                <td class="px-6 py-4">
                    <a href="details.php?id=<?= $sale['sale_id'] ?>" class="text-blue-500">Detail</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../../templates/footer.php'; ?> 