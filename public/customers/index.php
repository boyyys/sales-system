<?php
require_once '../../config/db.php';
require_once '../../templates/header.php';

$customers = $pdo->query("SELECT * FROM customers ORDER BY name")->fetchAll();
?>

<h1 class="text-2xl font-bold mb-4">Manajemen Pelanggan</h1>

<table class="min-w-full bg-white rounded shadow">
    <!-- Tabel daftar pelanggan -->
</table>

<?php require_once '../../templates/footer.php'; ?>