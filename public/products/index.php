<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<h1 class="text-2xl font-bold mb-4">Daftar Produk</h1>
<a href="create.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
    + Tambah Produk
</a>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left">Nama</th>
                <th class="px-6 py-3 text-left">Kategori</th>
                <th class="px-6 py-3 text-left">Harga</th>
                <th class="px-6 py-3 text-left">Stok</th>
                <th class="px-6 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr class="border-b">
                    <td class="px-6 py-4"><?= htmlspecialchars($product['name']) ?></td>
                    <td class="px-6 py-4"><?= $product['category'] ?></td>
                    <td class="px-6 py-4">Rp <?= number_format($product['sale_price'], 0, ',', '.') ?></td>
                    <td class="px-6 py-4"><?= $product['stock'] ?></td>
                    <td class="px-6 py-4">
                        <a href="edit.php?id=<?= $product['product_id'] ?>" class="text-blue-500">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../templates/footer.php'; ?>`