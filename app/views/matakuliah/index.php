<?php
ob_start();
?>

<h1 class="mb-4">Daftar Mata Kuliah</h1>

<a href="/si-akademik/public/matakuliah/create"
   class="btn btn-primary mb-3">
    Tambah Mata Kuliah
</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Mata Kuliah</th>
            <th>SKS</th>
            <th>Prodi</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php $no = 1; ?>

        <?php foreach ($matakuliah as $mk): ?>
            <tr>
                <td><?= $no++ ?></td>

                <td>
                    <?= htmlspecialchars($mk['kode']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($mk['nama']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($mk['sks']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($mk['kode_prodi']) ?>
                    -
                    <?= htmlspecialchars($mk['nama_prodi']) ?>
                </td>

                <td>
                    <a href="/si-akademik/public/matakuliah/edit/<?= $mk['id'] ?>"
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form method="POST"
                          action="/si-akademik/public/matakuliah/delete/<?= $mk['id'] ?>"
                          style="display:inline;"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                        <button type="submit"
                                class="btn btn-danger btn-sm">
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