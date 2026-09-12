<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">

<h2>Edit Mahasiswa</h2>

<form method="POST" action="<?= BASE_URL ?>/mahasiswa/update">

    <input type="hidden" name="id" value="<?= $mahasiswa['id'] ?>">

    <div class="mb-3">
        <label>NIM</label>
        <input type="text"
               name="nim"
               class="form-control"
               value="<?= htmlspecialchars($mahasiswa['nim']) ?>"
               required>
    </div>

    <div class="mb-3">
        <label>Nama</label>
        <input type="text"
               name="nama"
               class="form-control"
               value="<?= htmlspecialchars($mahasiswa['nama']) ?>"
               required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email"
               name="email"
               class="form-control"
               value="<?= htmlspecialchars($mahasiswa['email']) ?>"
               required>
    </div>

    <div class="mb-3">
        <label>Prodi</label>

        <select name="prodi_id" class="form-control" required>

            <option value="">-- Pilih Prodi --</option>

            <?php foreach ($prodi as $p): ?>

                <option value="<?= $p['id'] ?>"
                    <?= $p['id'] == $mahasiswa['prodi_id'] ? 'selected' : '' ?>>

                    <?= htmlspecialchars($p['kode']) ?>
                    -
                    <?= htmlspecialchars($p['nama']) ?>

                </option>

            <?php endforeach; ?>

        </select>
    </div>

    <div class="mb-3">
        <label>Angkatan</label>
        <input type="number"
               name="angkatan"
               class="form-control"
               value="<?= htmlspecialchars($mahasiswa['angkatan']) ?>"
               required>
    </div>

    <div class="mb-3">
        <label>Status</label>

        <select name="status" class="form-control" required>

            <option value="aktif"
                <?= $mahasiswa['status'] === 'aktif' ? 'selected' : '' ?>>
                Aktif
            </option>

            <option value="nonaktif"
                <?= $mahasiswa['status'] === 'nonaktif' ? 'selected' : '' ?>>
                Nonaktif
            </option>

        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        Update
    </button>

    <a href="<?= BASE_URL ?>/mahasiswa"
       class="btn btn-secondary">
        Kembali
    </a>
    

</form>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
