<?php
ob_start();
?>

<h1 class="mb-4">Tambah Program Studi</h1>

<form method="POST" action="/si-akademik/public/prodi">

    <div class="mb-3">
        <label for="kode" class="form-label">Kode Prodi</label>
        <input
            type="text"
            name="kode"
            id="kode"
            class="form-control"
            required
        >
    </div>

    <div class="mb-3">
        <label for="nama" class="form-label">Nama Program Studi</label>
        <input
            type="text"
            name="nama"
            id="nama"
            class="form-control"
            required
        >
    </div>

    <button type="submit" class="btn btn-primary">
        Simpan
    </button>

    <a href="/si-akademik/public/prodi" class="btn btn-secondary">
        Kembali
    </a>

</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>