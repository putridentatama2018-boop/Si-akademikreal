<!DOCTYPE html>
<html>
<head>
    <title>Data Buku</title>
</head>
<body>

<h1>Data Buku</h1>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Tahun Terbit</th>
        </tr>
    </thead>

    <tbody>
        <?php $no = 1; ?>

        <?php foreach ($buku as $item): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($item['judul']) ?></td>
                <td><?= htmlspecialchars($item['penulis']) ?></td>
                <td><?= htmlspecialchars($item['penerbit']) ?></td>
                <td><?= htmlspecialchars($item['tahun_terbit']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>