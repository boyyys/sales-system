<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

$sale_id = $_GET['id'] ?? null;
$sale = $pdo->query("
    SELECT s.*, c.name AS customer_name 
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.customer_id
    WHERE s.sale_id = $sale_id
")->fetch();

$items = $pdo->query("
    SELECT si.*, p.name 
    FROM sale_items si
    JOIN products p ON si.product_id = p.product_id
    WHERE si.sale_id = $sale_id
")->fetchAll();
?>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Detail Transaksi #<?= $sale['invoice_number'] ?></h2>

    <div class="mb-6">
        <p>Tanggal: <?= date('d/m/Y H:i', strtotime($sale['sale_date'])) ?></p>
        <p>Pelanggan: <?= $sale['customer_name'] ?? '-' ?></p>
        <p>Total: Rp <?= number_format($sale['total_amount'], 0, ',', '.') ?></p>
    </div>

    <h3 class="text-xl font-bold mb-3">Item yang Dibeli</h3>
    <table class="min-w-full">
        <thead>
            <tr>
                <th class="text-left">Produk</th>
                <th class="text-left">Qty</th>
                <th class="text-left">Harga</th>
                <th class="text-left">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr class="border-b">
                    <td class="py-2"><?= $item['name'] ?></td>
                    <td class="py-2"><?= $item['quantity'] ?></td>
                    <td class="py-2">Rp <?= number_format($item['sale_price'], 0, ',', '.') ?></td>
                    <td class="py-2">Rp <?= number_format($item['quantity'] * $item['sale_price'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../templates/footer.php'; ?>