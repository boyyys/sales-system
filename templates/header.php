<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
$base_url = '/thrift-system/public';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Thrift Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="<?= $base_url ?>/" class="text-xl font-bold">ThriftKu</a>
            <div class="space-x-4">
                <a href="<?= $base_url ?>/" class="hover:text-gray-300">Beranda</a>
                <a href="<?= $base_url ?>/products/" class="hover:text-gray-300">Produk</a>
                <a href="<?= $base_url ?>/sales/" class="hover:text-gray-300">Penjualan</a>
            </div>
            <?php if (isset($_SESSION['username'])): ?>
                <div>Halo, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong> | <a href="<?= $base_url ?>/auth/logout.php" class="underline">Logout</a></div>
            <?php endif; ?>
        </div>
    </nav>
    <main class="container mx-auto p-4 flex-grow">