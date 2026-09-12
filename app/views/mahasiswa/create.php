<?php

ob_start();

?>

<h1 class="mb-4">Tambah Mahasiswa</h1>

<form action="/si-akademik/public/mahasiswa" method="POST">

    <div class="mb-3">
        <label for="nim" class="form-label">NIM</label>
        <input type="text"
               name="nim"
               id="nim"
               class="form-control"
               required>
    </div>

    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text"
               name="nama"
               id="nama"
               class="form-control"
               required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email"
               name="email"
               id="email"
               class="form-control"
               required>
    </div>

    <div class="mb-3">
        <label for="prodi_id" class="form-label">Prodi</label>

        <select name="prodi_id"
                id="prodi_id"
                class="form-select"
                required>

            <option value="">-- Pilih Prodi --</option>

            <?php foreach ($prodi as $p): ?>

                <option value="<?= $p['id'] ?>">
                    <?= htmlspecialchars($p['kode']) ?>
                    -
                    <?= htmlspecialchars($p['nama']) ?>
                </option>

            <?php endforeach; ?>

        </select>
    </div>

    <div class="mb-3">
        <label for="angkatan" class="form-label">Angkatan</label>

        <input type="number"
               name="angkatan"
               id="angkatan"
               class="form-control"
               required>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status</label>

        <select name="status"
                id="status"
                class="form-select">

            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>

        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        Simpan
    </button>

    <a href="/si-akademik/public/mahasiswa"
       class="btn btn-secondary">
        Kembali
    </a>

</form>

<?php

$content = ob_get_clean();

include __DIR__ . '/../layouts/main.php';

?>