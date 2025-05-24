<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

// Ambil data produk
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<p class='text-red-500'>Produk tidak ditemukan.</p>";
    require_once '../../templates/footer.php';
    exit;
}

// Ambil daftar supplier
$suppliers = $pdo->query("SELECT supplier_id, name FROM suppliers")->fetchAll();

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = htmlspecialchars(trim($_POST['name']));
    $category    = ucfirst(strtolower(trim($_POST['category'])));
    $cost_price  = (float) $_POST['cost_price'];
    $sale_price  = (float) $_POST['sale_price'];
    $stock       = (int) $_POST['stock'];
    $description = !empty($_POST['description']) ? htmlspecialchars(trim($_POST['description'])) : NULL;
    $supplier_id = !empty($_POST['supplier_id']) ? (int) $_POST['supplier_id'] : NULL;

    $allowed_categories = ['Pakaian', 'Celana', 'Aksesoris', 'Sepatu', 'Tas', 'Jaket'];
    if (!in_array($category, $allowed_categories)) {
        die("Kategori tidak valid.");
    }

    try {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, cost_price = ?, sale_price = ?, stock = ?, description = ?, supplier_id = ? WHERE product_id = ?");
        $stmt->execute([$name, $category, $cost_price, $sale_price, $stock, $description, $supplier_id, $id]);

        header("Location: index.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Error mengupdate produk: " . $e->getMessage());
    }
}
?>

<h1 class="text-2xl font-bold mb-4">Edit Produk</h1>

<form method="POST" class="max-w-lg bg-white p-6 rounded shadow">
    <div class="mb-4">
        <label class="block mb-2 font-semibold">Nama Produk</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required class="w-full p-2 border rounded">
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Kategori</label>
        <select name="category" class="w-full p-2 border rounded" required>
            <?php
            $categories = ['Pakaian', 'Celana', 'Aksesoris', 'Sepatu', 'Tas', 'Jaket'];
            foreach ($categories as $cat) {
                $selected = (strtolower($product['category']) === strtolower($cat)) ? 'selected' : '';
                echo "<option value=\"$cat\" $selected>$cat</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Deskripsi</label>
        <textarea name="description" class="w-full p-2 border rounded" rows="3" placeholder="Tulis deskripsi produk..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block mb-2 font-semibold">Harga Beli</label>
            <input type="number" step="0.01" name="cost_price" value="<?= $product['cost_price'] ?>" required class="w-full p-2 border rounded">
        </div>
        <div>
            <label class="block mb-2 font-semibold">Harga Jual</label>
            <input type="number" step="0.01" name="sale_price" value="<?= $product['sale_price'] ?>" required class="w-full p-2 border rounded">
        </div>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Stok</label>
        <input type="number" name="stock" value="<?= $product['stock'] ?>" required class="w-full p-2 border rounded">
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Supplier</label>
        <select name="supplier_id" class="w-full p-2 border rounded">
            <option value="">-- Pilih Supplier --</option>
            <?php foreach ($suppliers as $supplier): ?>
                <option value="<?= $supplier['supplier_id'] ?>" <?= ($product['supplier_id'] == $supplier['supplier_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($supplier['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
        Perbarui Produk
    </button>
</form>

<?php require_once '../../templates/footer.php'; ?>