<?php
ob_start();
?>

<h1 class="mb-4">Edit Mata Kuliah</h1>

<form method="POST" action="/si-akademik/public/matakuliah/update/<?= $matakuliah['id'] ?>">

    <div class="mb-3">
        <label for="kode" class="form-label">Kode Mata Kuliah</label>
        <input
            type="text"
            name="kode"
            id="kode"
            class="form-control"
            value="<?= htmlspecialchars($matakuliah['kode']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label for="nama" class="form-label">Nama Mata Kuliah</label>
        <input
            type="text"
            name="nama"
            id="nama"
            class="form-control"
            value="<?= htmlspecialchars($matakuliah['nama']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label for="sks" class="form-label">SKS</label>
        <input
            type="number"
            name="sks"
            id="sks"
            class="form-control"
            value="<?= htmlspecialchars($matakuliah['sks']) ?>"
            min="1"
            max="6"
            required
        >
    </div>

    <div class="mb-3">
        <label for="prodi_id" class="form-label">Program Studi</label>

        <select
            name="prodi_id"
            id="prodi_id"
            class="form-select"
            required
        >
            <?php foreach ($prodi as $p): ?>
                <option
                    value="<?= $p['id'] ?>"
                    <?= $p['id'] == $matakuliah['prodi_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($p['kode']) ?>
                    -
                    <?= htmlspecialchars($p['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        Update
    </button>

    <a
        href="/si-akademik/public/matakuliah"
        class="btn btn-secondary"
    >
        Kembali
    </a>

</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>