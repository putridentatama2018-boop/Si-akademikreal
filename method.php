<!DOCTYPE html>
<html>
<head>
    <title>Deteksi Method Request</title>
</head>
<body>

    <h1>Deteksi Method Request</h1>

    <h3>Request GET</h3>

    <form action="method.php" method="GET">
        <button type="submit">Kirim GET</button>
    </form>

    <br>

    <h3>Request POST</h3>

    <form action="method.php" method="POST">
        <button type="submit">Kirim POST</button>
    </form>

    <hr>

    <?php

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'GET') {
        echo "<h2>Request menggunakan GET</h2>";
        echo "<p>Method yang digunakan adalah GET.</p>";
    } elseif ($method == 'POST') {
        echo "<h2>Request menggunakan POST</h2>";
        echo "<p>Method yang digunakan adalah POST.</p>";
    }

    ?>

</body>
</html>