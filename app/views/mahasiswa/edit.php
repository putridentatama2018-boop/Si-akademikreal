<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<h1 class="mb-4">Edit Mahasiswa</h1>

<?php if ($flash): ?>

    <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show"
         role="alert">

        <?= htmlspecialchars($flash['message']) ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"></button>

    </div>

<?php endif; ?>


<form id="formEditMahasiswa"
      enctype="multipart/form-data">


    <!-- ID -->
    <input type="hidden"
           id="id"
           value="<?= htmlspecialchars($mahasiswa['id']) ?>">


    <!-- NIM -->
    <div class="mb-3">

        <label for="nim"
               class="form-label">

            NIM

        </label>

        <input type="text"
               name="nim"
               id="nim"
               class="form-control"
               value="<?= htmlspecialchars($mahasiswa['nim']) ?>"
               required>

    </div>


    <!-- Nama -->
    <div class="mb-3">

        <label for="nama"
               class="form-label">

            Nama

        </label>

        <input type="text"
               name="nama"
               id="nama"
               class="form-control"
               value="<?= htmlspecialchars($mahasiswa['nama']) ?>"
               required>

    </div>


    <!-- Email -->
    <div class="mb-3">

        <label for="email"
               class="form-label">

            Email

        </label>

        <input type="email"
               name="email"
               id="email"
               class="form-control"
               value="<?= htmlspecialchars($mahasiswa['email']) ?>"
               required>

    </div>


    <!-- Program Studi -->
    <div class="mb-3">

        <label for="prodi_id"
               class="form-label">

            Program Studi

        </label>

        <select name="prodi_id"
                id="prodi_id"
                class="form-select"
                required>

            <option value="">
                -- Pilih Prodi --
            </option>

            <?php foreach ($prodi as $p): ?>

                <option value="<?= htmlspecialchars($p['id']) ?>"
                    <?= $p['id'] == $mahasiswa['prodi_id'] ? 'selected' : '' ?>>

                    <?= htmlspecialchars($p['kode']) ?>
                    -
                    <?= htmlspecialchars($p['nama']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>


    <!-- Angkatan -->
    <div class="mb-3">

        <label for="angkatan"
               class="form-label">

            Angkatan

        </label>

        <input type="number"
               name="angkatan"
               id="angkatan"
               class="form-control"
               value="<?= htmlspecialchars($mahasiswa['angkatan']) ?>"
               required>

    </div>


    <!-- Status -->
    <div class="mb-3">

        <label for="status"
               class="form-label">

            Status

        </label>

        <select name="status"
                id="status"
                class="form-select"
                required>

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


    <!-- Foto Saat Ini -->
    <?php if (!empty($mahasiswa['foto'])): ?>

        <div class="mb-3">

            <label class="form-label d-block">
                Foto Saat Ini
            </label>

            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($mahasiswa['foto']) ?>"
                 alt="Foto <?= htmlspecialchars($mahasiswa['nama']) ?>"
                 width="120"
                 height="120"
                 style="object-fit: cover;"
                 class="img-thumbnail rounded mb-2">

        </div>

    <?php endif; ?>


    <!-- Foto Baru -->
    <div class="mb-3">

        <label for="foto"
               class="form-label">

            <?= !empty($mahasiswa['foto'])
                ? 'Ganti Foto'
                : 'Foto Mahasiswa' ?>

        </label>

        <input type="file"
               name="foto"
               id="foto"
               class="form-control"
               accept=".jpg,.jpeg,.png,image/jpeg,image/png">

        <div class="form-text">

            Format yang diperbolehkan:
            JPG, JPEG, PNG.
            Maksimal 2 MB.
            Kosongkan jika tidak ingin mengganti foto.

        </div>

    </div>


    <!-- Tombol -->
    <button type="submit"
            class="btn btn-primary">

        Simpan

    </button>


    <a href="/si-akademik/public/mahasiswa"
       class="btn btn-secondary">

        Kembali

    </a>

</form>


<script>

const formEditMahasiswa =
    document.getElementById('formEditMahasiswa');


formEditMahasiswa.addEventListener(
    'submit',
    async function(event) {

        event.preventDefault();


        const id =
            document.getElementById('id').value;


        /*
         * Membuat FormData
         */
        const formData =
            new FormData(formEditMahasiswa);


        try {

            /*
             * Kirim data ke endpoint mahasiswa.
             *
             * Tidak menggunakan BASE_URL
             * agar tidak terjadi error JavaScript.
             */
            const response = await fetch(
                `/si-akademik/public/mahasiswa?id=${id}`,
                {
                    method: 'PUT',

                    headers: {
                        'Authorization':
                            `Bearer ${localStorage.getItem('token')}`
                    },

                    /*
                     * Jangan tambahkan Content-Type.
                     * Browser akan membuat multipart boundary.
                     */
                    body: formData
                }
            );


            /*
             * Ambil response server.
             */
            const responseText =
                await response.text();


            console.log(
                'Status:',
                response.status
            );


            console.log(
                'Response server:',
                responseText
            );


            /*
             * Ubah response menjadi JSON.
             */
            let hasil;

            try {

                hasil =
                    JSON.parse(responseText);

            } catch (jsonError) {

                alert(
                    'Response server bukan JSON:\n\n' +
                    responseText
                );

                return;

            }


            /*
             * Berhasil
             */
            if (response.ok) {

                alert(
                    hasil.message ||
                    'Data mahasiswa berhasil diupdate.'
                );


                window.location.href =
                    '/si-akademik/public/mahasiswa';

            }


            /*
             * Gagal
             */
            else {

                alert(
                    hasil.message ||
                    'Data mahasiswa gagal diupdate.'
                );

            }


        } catch (error) {

            console.error(
                'Error:',
                error
            );


            alert(
                'Terjadi kesalahan saat menghubungi server.\n\n' +
                error.message
            );

        }

    }
);

</script>


<?php

$content = ob_get_clean();

include __DIR__ . '/../layouts/main.php';

?>