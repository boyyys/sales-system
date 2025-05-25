<?php
session_start();
session_destroy();
header("Location: /thrift-system/public/auth/login.php");
exit;
?>