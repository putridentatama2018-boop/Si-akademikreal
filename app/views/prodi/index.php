<?php
ob_start();
?>

<h1 class="mb-4">Daftar Program Studi</h1>

<a href="/si-akademik/public/prodi/create" class="btn btn-primary mb-3">
    Tambah Prodi
</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Program Studi</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php $no = 1; ?>

        <?php foreach ($prodi as $p): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($p['kode']) ?></td>
                <td><?= htmlspecialchars($p['nama']) ?></td>
                <td>
                    <a href="/si-akademik/public/prodi/edit/<?= $p['id'] ?>"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form method="POST"
                        action="/si-akademik/public/prodi/delete/<?= $p['id'] ?>"
                        style="display:inline;"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                        <button type="submit" class="btn btn-danger btn-sm">
                            Hapus
                        </button>

                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>