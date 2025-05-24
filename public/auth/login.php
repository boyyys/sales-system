<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $user = $pdo->query("SELECT * FROM users WHERE username = '$username'")->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: /");
    } else {
        $error = "Username atau password salah";
    }
}
?>

<!-- Form login -->