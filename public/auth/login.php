<?php
session_start();
require_once '../../config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: /thrift-system/public/");
    exit;
}

$error = '';
$debug = ''; // Menyimpan pesan debug

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user) {
        $debug .= "Username Ditemukan: " . htmlspecialchars($username) . "<br>";
        $debug .= "Password Input: " . htmlspecialchars($password) . "<br>";
        $debug .= "Hash dari DB: " . htmlspecialchars($user['password']) . "<br>";
        $verify = password_verify($password, $user['password']);
        $debug .= "Hasil password_verify: " . ($verify ? "✅ cocok" : "❌ tidak cocok") . "<br>";

        if ($verify) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: /thrift-system/public/");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    } else {
        $debug .= "Username TIDAK ditemukan.<br>";
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Thrift System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center mb-6">Login Sistem Thrift</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 p-2 rounded mb-4 text-sm">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($debug)): ?>
            <div class="bg-gray-100 border border-gray-300 text-sm text-gray-700 p-3 rounded mb-4">
                <?= $debug ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block mb-1 text-sm text-gray-700">Username</label>
                <input name="username" type="text" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-6">
                <label class="block mb-1 text-sm text-gray-700">Password</label>
                <input name="password" type="password" required class="w-full border rounded px-3 py-2">
            </div>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold">Login</button>
        </form>
    </div>
</body>

</html>