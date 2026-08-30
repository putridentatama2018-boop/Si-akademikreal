<?php

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

echo "<h2>Hasil Login</h2>";
echo "Username yang dikirim: " . htmlspecialchars($username);

?>