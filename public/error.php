<?php
require_once '../templates/header.php';
?>

<div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded">
    <h2 class="text-xl font-bold mb-2">Terjadi Kesalahan!</h2>
    <p><?= $_SESSION['error'] ?? 'Kesalahan tidak diketahui' ?></p>
</div>

<?php require_once '../templates/footer.php'; ?>