<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

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
        $stmt = $pdo->prepare("INSERT INTO products (name, category, cost_price, sale_price, stock, description, supplier_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $category, $cost_price, $sale_price, $stock, $description, $supplier_id]);

        header("Location: index.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Error menambahkan produk: " . $e->getMessage());
    }
}
?>

<h1 class="text-2xl font-bold mb-4">Tambah Produk</h1>

<form method="POST" class="max-w-lg bg-white p-6 rounded shadow">
    <div class="mb-4">
        <label class="block mb-2 font-semibold">Nama Produk</label>
        <input type="text" name="name" required class="w-full p-2 border rounded">
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Kategori</label>
        <select name="category" class="w-full p-2 border rounded" required>
            <option value="">-- Pilih Kategori --</option>
            <?php
            $categories = ['Pakaian', 'Celana', 'Aksesoris', 'Sepatu', 'Tas', 'Jaket'];
            foreach ($categories as $cat) {
                echo "<option value=\"$cat\">$cat</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Deskripsi</label>
        <textarea name="description" class="w-full p-2 border rounded" rows="3" placeholder="Tulis deskripsi produk..."></textarea>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block mb-2 font-semibold">Harga Beli</label>
            <input type="number" step="0.01" name="cost_price" required class="w-full p-2 border rounded">
        </div>
        <div>
            <label class="block mb-2 font-semibold">Harga Jual</label>
            <input type="number" step="0.01" name="sale_price" required class="w-full p-2 border rounded">
        </div>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Stok</label>
        <input type="number" name="stock" required class="w-full p-2 border rounded">
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Supplier</label>
        <select name="supplier_id" class="w-full p-2 border rounded">
            <option value="">-- Pilih Supplier --</option>
            <?php foreach ($suppliers as $supplier): ?>
                <option value="<?= $supplier['supplier_id'] ?>">
                    <?= htmlspecialchars($supplier['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        Simpan Produk
    </button>
</form>

<?php require_once '../../templates/footer.php'; ?> 