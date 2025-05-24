<?php
$host = 'localhost';
$db   = 'thrift_system';
$user = 'root';
$pass = ''; // Ganti sesuai password MySQL kamu
$charset = 'utf8'; // <-- gunakan utf8 untuk mendukung utf8_general_ci

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
