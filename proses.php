<?php

$keyword = $_GET['keyword'] ?? '';

echo "<h2>Hasil Pencarian</h2>";
echo "Anda mencari: " . htmlspecialchars($keyword);

?> 