<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

// Proses Form Input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = htmlspecialchars(trim($_POST['name']));
    $category    = $_POST['category'];
    $cost_price  = (float) $_POST['cost_price'];
    $sale_price  = (float) $_POST['sale_price'];
    $stock       = isset($_POST['stock']) ? (int) $_POST['stock'] : 0; // fallback

    try {
        $stmt = $pdo->prepare("
            INSERT INTO products (name, category, cost_price, sale_price, stock)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $category, $cost_price, $sale_price, $stock]);

        header("Location: index.php?success=1");
        exit;
    } catch (PDOException $e) {
        die("Error menambah produk: " . $e->getMessage());
    }
}
?>

<h1 class="text-2xl font-bold mb-4">Tambah Produk Baru</h1>

<form method="POST" class="max-w-lg bg-white p-6 rounded shadow">
    <div class="mb-4">
        <label class="block mb-2 font-semibold">Nama Produk</label>
        <input type="text" name="name" required
            class="w-full p-2 border rounded" placeholder="Contoh: Kemeja Denim">
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Kategori</label>
        <select name="category" class="w-full p-2 border rounded" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Pakaian">Pakaian</option>
            <option value="Celana">Celana</option>
            <option value="Aksesoris">Aksesoris</option>
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block mb-2 font-semibold">Harga Beli</label>
            <input type="number" name="cost_price" step="0.01" required
                class="w-full p-2 border rounded" placeholder="Rp">
        </div>
        <div>
            <label class="block mb-2 font-semibold">Harga Jual</label>
            <input type="number" name="sale_price" step="0.01" required
                class="w-full p-2 border rounded" placeholder="Rp">
        </div>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-semibold">Stok</label>
        <input type="number" name="stock" required
            class="w-full p-2 border rounded" placeholder="Misal: 10">
    </div>

    <button type="submit"
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        Simpan Produk
    </button>
</form>

<?php require_once '../../templates/footer.php'; ?>