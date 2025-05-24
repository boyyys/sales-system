<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

// Ambil data produk dengan nama supplier (LEFT JOIN)
$stmt = $pdo->query("
    SELECT p.*, s.name AS supplier_name
    FROM products p
    LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
    ORDER BY p.created_at DESC
");
$products = $stmt->fetchAll();
?>

<h1 class="text-2xl font-bold mb-4">Daftar Produk</h1>
<a href="create.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
    + Tambah Produk
</a>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full">
        <thead class="bg-gray-50 text-sm">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Kategori</th>
                <th class="px-4 py-3 text-left">Harga Beli</th>
                <th class="px-4 py-3 text-left">Harga Jual</th>
                <th class="px-4 py-3 text-left">Stok</th>
                <th class="px-4 py-3 text-left">Supplier</th>
                <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3"><?= htmlspecialchars($product['name']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($product['category']) ?></td>
                        <td class="px-4 py-3">Rp <?= number_format($product['cost_price'], 0, ',', '.') ?></td>
                        <td class="px-4 py-3">Rp <?= number_format($product['sale_price'], 0, ',', '.') ?></td>
                        <td class="px-4 py-3"><?= $product['stock'] ?></td>
                        <td class="px-4 py-3"><?= $product['supplier_name'] ?? '—' ?></td>
                        <td class="px-4 py-3">
                            <a href="edit.php?id=<?= $product['product_id'] ?>" class="text-blue-500 hover:underline">Edit</a>
                            |
                            <a href="delete.php?id=<?= $product['product_id'] ?>" class="text-red-500 hover:underline"
                                onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        Belum ada produk yang ditambahkan.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../templates/footer.php'; ?>