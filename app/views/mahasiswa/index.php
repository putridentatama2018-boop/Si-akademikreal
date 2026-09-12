<?php

ob_start();

?>

<h1 class="mb-4">Daftar Mahasiswa</h1>
<form method="GET" action="/si-akademik/public/mahasiswa" class="mb-3">
    <div class="input-group">
        <input
            type="text"
            name="q"
            class="form-control"
            placeholder="Cari berdasarkan NIM atau Nama..."
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
        >
        <button type="submit" class="btn btn-primary">
            Cari
        </button>
        <a href="/si-akademik/public/mahasiswa" class="btn btn-secondary">
            Reset
        </a>
    </div>
</form>


<a href="/si-akademik/public/mahasiswa/create" class="btn btn-primary mb-3">
    Tambah Mahasiswa
</a>

<table class="table table-bordered table-striped">

    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Prodi</th>
            <th>Angkatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

        <?php $no = 1; ?>

        <?php foreach ($mahasiswa as $mhs): ?>

        <tr>

            <td>
                <?= $no++ ?>
            </td>

            <td>
                <?= htmlspecialchars($mhs['nim']) ?>
            </td>

            <td>
                <?= htmlspecialchars($mhs['nama']) ?>
            </td>

            <td>
                <?= htmlspecialchars($mhs['email']) ?>
            </td>

            <td>
                <?= htmlspecialchars($mhs['kode_prodi']) ?>
                -
                <?= htmlspecialchars($mhs['nama_prodi']) ?>
            </td>

            <td>
                <?= htmlspecialchars($mhs['angkatan']) ?>
            </td>

            <td>
                <?= htmlspecialchars($mhs['status']) ?>
            </td>

            <td>

                <a href="/si-akademik/public/mahasiswa/edit/<?= $mhs['id'] ?>"
                   class="btn btn-warning btn-sm">
                    Edit
                </a>

                <a href="/si-akademik/public/mahasiswa/delete/<?= $mhs['id'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                    Hapus
                </a>

            </td>

        </tr>

        <?php endforeach; ?>

    </tbody>

</table>

<?php

$content = ob_get_clean();

include __DIR__ . '/../layouts/main.php';

?>