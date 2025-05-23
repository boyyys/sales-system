<?php
session_start();
include 'config/db.php';

// Redirect jika bukan POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: sales.php");
    exit;
}

try {
    $pdo->beginTransaction();

    // Validasi input utama
    $customerName = htmlspecialchars($_POST['customer_name']);
    if (empty($customerName)) {
        throw new Exception("Nama pelanggan wajib diisi!");
    }

    // Insert header penjualan
    $stmt = $pdo->prepare("INSERT INTO sales (customer_name, total_amount, profit) VALUES (?, ?, ?)");
    $stmt->execute([$customerName, 0, 0]);
    $saleId = $pdo->lastInsertId();

    $totalAmount = 0;
    $totalProfit = 0;
    $items = [];

    // Proses setiap item
    foreach ($_POST['product_id'] as $key => $productId) {
        $quantity = (int)$_POST['quantity'][$key];

        // Skip item kosong
        if ($quantity < 1 || empty($productId)) continue;

        // Ambil data produk dengan row locking
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? FOR UPDATE");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        // Validasi stok
        if (!$product) {
            throw new Exception("Produk tidak valid!");
        }
        if ($product['stock'] < $quantity) {
            throw new Exception("Stok tidak cukup untuk {$product['name']} (Stok tersedia: {$product['stock']})");
        }

        // Hitung harga dan profit
        $price = $product['price'];
        $costPrice = $product['cost_price'];
        $subtotal = $price * $quantity;
        $profit = ($price - $costPrice) * $quantity;

        // Simpan item penjualan
        $stmt = $pdo->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price, cost_price) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$saleId, $productId, $quantity, $price, $costPrice]);

        // Update total
        $totalAmount += $subtotal;
        $totalProfit += $profit;

        // Update stok produk
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $productId]);

        // Simpan data untuk receipt
        $items[] = [
            'name' => $product['name'],
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal
        ];
    }

    // Validasi minimal 1 item
    if (count($items) === 0) {
        throw new Exception("Minimal harus ada 1 item dalam transaksi!");
    }

    // Update total penjualan
    $stmt = $pdo->prepare("UPDATE sales SET total_amount = ?, profit = ? WHERE id = ?");
    $stmt->execute([$totalAmount, $totalProfit, $saleId]);

    $pdo->commit();

    // Simpan data untuk receipt
    $_SESSION['receipt'] = [
        'sale_id' => $saleId,
        'customer' => $customerName,
        'items' => $items,
        'total' => $totalAmount,
        'profit' => $totalProfit,
        'date' => date('d/m/Y H:i:s')
    ];

    header("Location: sales.php?success=1");
} catch (Exception $e) {
    $pdo->rollBack();
    header("Location: sales.php?error=" . urlencode($e->getMessage()));
} finally {
    exit;
}
?>

<?php if (isset($_SESSION['receipt'])): ?>
    <div class="receipt-container bg-white p-6 rounded-lg shadow-md max-w-md mx-auto mt-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold">Thrift Shop</h2>
            <p class="text-gray-600">Jl. Thrift Fashion No. 123</p>
        </div>

        <div class="mb-4">
            <p class="font-semibold">No. Transaksi: #<?= $_SESSION['receipt']['sale_id'] ?></p>
            <p>Pelanggan: <?= htmlspecialchars($_SESSION['receipt']['customer']) ?></p>
            <p>Tanggal: <?= $_SESSION['receipt']['date'] ?></p>
        </div>

        <table class="w-full mb-4">
            <thead>
                <tr class="border-b">
                    <th class="text-left">Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['receipt']['items'] as $item): ?>
                    <tr class="border-b">
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td class="text-right"><?= $item['quantity'] ?></td>
                        <td class="text-right">$<?= number_format($item['price'], 2) ?></td>
                        <td class="text-right">$<?= number_format($item['subtotal'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-right font-semibold">
            <p>Total: $<?= number_format($_SESSION['receipt']['total'], 2) ?></p>
            <p class="text-green-600">Profit: $<?= number_format($_SESSION['receipt']['profit'], 2) ?></p>
        </div>

        <div class="text-center mt-6 text-sm text-gray-500">
            Terima kasih telah berbelanja!
        </div>
    </div>

    <div class="text-center mt-4">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Cetak Receipt
        </button>
    </div>

    <?php unset($_SESSION['receipt']); ?>
<?php endif; ?>