<?php
require_once __DIR__ . '/../config/db.php';
$base_url = '/thrift-system/public'; // Ganti jika folder proyek berbeda
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thrift Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?= $base_url ?>/" class="text-xl font-bold">ThriftKu</a>
            <div class="space-x-4">
                <a href="<?= $base_url ?>/products/" class="hover:text-gray-300">Produk</a>
                <a href="<?= $base_url ?>/sales/" class="hover:text-gray-300">Penjualan</a>
            </div>
        </div>
    </nav>
    <div class="container mx-auto p-4">