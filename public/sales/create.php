<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        // Insert sales header
        $stmt = $pdo->prepare("INSERT INTO sales (...) VALUES (...)");
        $stmt->execute([...]);
        
        // Process items
        foreach ($_POST['items'] as $item) {
            // Validate and insert items
        }
        
        $pdo->commit();
        header("Location: index.php?success=1");
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}

$products = $pdo->query("SELECT * FROM products WHERE stock > 0")->fetchAll();
?>

<!-- Form transaksi dengan JS untuk dynamic items -->
<script>
function addItemRow() {
    const container = document.getElementById('items-container');
    const newRow = `
        <div class="flex gap-4 mb-4">
            <select name="items[][product_id]" class="flex-1 border p-2 rounded">
                <?php foreach ($products as $p): ?>
                <option value="<?= $p['product_id'] ?>">
                    <?= htmlspecialchars($p['name']) ?> (Stok: <?= $p['stock'] ?>)
                </option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="items[][quantity]" class="w-24 border p-2 rounded" min="1">
            <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-3 py-2 rounded">
                Hapus
            </button>
        </div>`;
    container.insertAdjacentHTML('beforeend', newRow);
}
</script>