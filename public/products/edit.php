<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

$id = $_GET['id'] ?? null;

// Validasi ID
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

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = htmlspecialchars(trim($_POST['name']));
    $category    = $_POST['category'];
    $cost_price  = (float) $_POST['cost_price'];
    $sale_price  = (float) $_POST['sale_price'];
    $stock       = (int) $_POST['stock'];

    try {
        $stmt = $pdo->prepare("UPDATE products SET 
            name = ?, category = ?, cost_price = ?, sale_price = ?, stock = ?
            WHERE product_id = ?");
        $stmt->execute([$name, $category, $cost_price, $sale_price, $stock, $id]);

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
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required
            class="w-full p-2 border rounded">
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Kategori</label>
        <select name="category" class="w-full p-2 border rounded" required>
            <option value="Pakaian" <?= $product['category'] === 'Pakaian' ? 'selected' : '' ?>>Pakaian</option>
            <option value="Celana" <?= $product['category'] === 'Celana' ? 'selected' : '' ?>>Celana</option>
            <option value="Aksesoris" <?= $product['category'] === 'Aksesoris' ? 'selected' : '' ?>>Aksesoris</option>
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block mb-2 font-semibold">Harga Beli</label>
            <input type="number" step="0.01" name="cost_price" value="<?= $product['cost_price'] ?>" required
                class="w-full p-2 border rounded">
        </div>
        <div>
            <label class="block mb-2 font-semibold">Harga Jual</label>
            <input type="number" step="0.01" name="sale_price" value="<?= $product['sale_price'] ?>" required
                class="w-full p-2 border rounded">
        </div>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Stok</label>
        <input type="number" name="stock" value="<?= $product['stock'] ?>" required
            class="w-full p-2 border rounded">
    </div>

    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
        Perbarui Produk
    </button>
</form>

<?php require_once '../../templates/footer.php'; ?>