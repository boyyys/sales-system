<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

$sale_id = $_GET['id'] ?? null;

if (!$sale_id || !is_numeric($sale_id)) {
    echo "<div class='text-red-500'>ID transaksi tidak valid.</div>";
    require_once '../../templates/footer.php';
    exit;
}

// Ambil header transaksi
$stmt = $pdo->prepare("
    SELECT s.*, c.name AS customer_name 
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.customer_id
    WHERE s.sale_id = ?
");
$stmt->execute([$sale_id]);
$sale = $stmt->fetch();

if (!$sale) {
    echo "<div class='text-red-500'>Transaksi tidak ditemukan.</div>";
    require_once '../../templates/footer.php';
    exit;
}

// Ambil item
$item_stmt = $pdo->prepare("
    SELECT si.*, p.name 
    FROM sale_items si
    JOIN products p ON si.product_id = p.product_id
    WHERE si.sale_id = ?
");
$item_stmt->execute([$sale_id]);
$items = $item_stmt->fetchAll();
?>

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Detail Transaksi #<?= htmlspecialchars($sale['invoice_number']) ?></h2>

    <div class="mb-6 text-sm">
        <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($sale['sale_date'])) ?></p>
        <p><strong>Pelanggan:</strong> <?= htmlspecialchars($sale['customer_name'] ?? '-') ?></p>
        <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($sale['payment_method']) ?></p>
        <p><strong>Total:</strong> Rp <?= number_format($sale['total_amount'], 0, ',', '.') ?></p>
        <p><strong>Keuntungan:</strong> Rp <?= number_format($sale['total_profit'], 0, ',', '.') ?></p>
    </div>

    <h3 class="text-xl font-semibold mb-3">Item yang Dibeli</h3>
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left px-4 py-2">Produk</th>
                <th class="text-left px-4 py-2">Qty</th>
                <th class="text-left px-4 py-2">Harga Satuan</th>
                <th class="text-left px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr class="border-b">
                    <td class="px-4 py-2"><?= htmlspecialchars($item['name']) ?></td>
                    <td class="px-4 py-2"><?= $item['quantity'] ?></td>
                    <td class="px-4 py-2">Rp <?= number_format($item['sale_price'], 0, ',', '.') ?></td>
                    <td class="px-4 py-2">Rp <?= number_format($item['quantity'] * $item['sale_price'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../templates/footer.php'; ?>
