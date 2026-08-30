<!DOCTYPE html>
<html>
<head>
    <title>SI Akademik</title>
</head>
<body>

    <h1>Selamat Datang di SI Akademik</h1>

    <h2>Pencarian Mahasiswa</h2>

    <form action="proses.php" method="GET">
        <label>Cari Mahasiswa:</label>
        <input type="text" name="keyword">
        <button type="submit">Cari</button>
    </form>

    <h2>Login</h2>

    <form action="login.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username">
        <br><br>

        <label>Password:</label>
        <input type="password" name="password">
        <br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html> 