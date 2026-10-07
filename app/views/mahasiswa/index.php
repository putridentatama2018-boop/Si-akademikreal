<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<h1 class="mb-4">Daftar Mahasiswa</h1>


<!-- NOTIFIKASI -->
<div id="notification"
     class="alert d-none alert-dismissible fade show"
     role="alert">

    <span id="notificationMessage"></span>

    <button type="button"
            class="btn-close"
            onclick="tutupNotifikasi()"
            aria-label="Close"></button>

</div>


<!-- FLASH MESSAGE -->
<?php if ($flash): ?>

    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show"
         role="alert">

        <span>
            <?= htmlspecialchars($flash['message']) ?>
        </span>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"></button>

    </div>

<?php endif; ?>


<!-- FORM PENCARIAN -->
<form method="GET"
      action="/si-akademik/public/mahasiswa"
      class="mb-3">

    <div class="input-group">

        <input
            type="text"
            name="q"
            class="form-control"
            placeholder="Cari berdasarkan NIM atau Nama..."
            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
        >

        <button type="submit"
                class="btn btn-primary">

            Cari

        </button>

        <a href="/si-akademik/public/mahasiswa"
           class="btn btn-secondary">

            Reset

        </a>

    </div>

</form>


<!-- TAMBAH MAHASISWA -->
<a href="/si-akademik/public/mahasiswa/create"
   class="btn btn-primary mb-3">

    Tambah Mahasiswa

</a>


<!-- TABEL -->
<table class="table table-bordered table-striped">

    <thead class="table-dark">

        <tr>

            <th>No</th>
            <th>Foto</th>
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


                <!-- FOTO -->
                <td class="text-center">

                    <?php if (!empty($mhs['foto'])): ?>

                        <img
                            src="/si-akademik/public/uploads/<?= htmlspecialchars($mhs['foto']) ?>"
                            alt="Foto <?= htmlspecialchars($mhs['nama']) ?>"
                            width="70"
                            height="70"
                            style="object-fit: cover;"
                            class="rounded"
                        >

                    <?php else: ?>

                        <span class="text-muted">
                            Tidak ada foto
                        </span>

                    <?php endif; ?>

                </td>


                <!-- NIM -->
                <td>
                    <?= htmlspecialchars($mhs['nim']) ?>
                </td>


                <!-- NAMA -->
                <td>
                    <?= htmlspecialchars($mhs['nama']) ?>
                </td>


                <!-- EMAIL -->
                <td>
                    <?= htmlspecialchars($mhs['email']) ?>
                </td>


                <!-- PRODI -->
                <td>
                    <?= htmlspecialchars($mhs['kode_prodi']) ?>
                    -
                    <?= htmlspecialchars($mhs['nama_prodi']) ?>
                </td>


                <!-- ANGKATAN -->
                <td>
                    <?= htmlspecialchars($mhs['angkatan']) ?>
                </td>


                <!-- STATUS -->
                <td>
                    <?= htmlspecialchars($mhs['status']) ?>
                </td>


                <!-- AKSI -->
                <td>

                    <!-- EDIT -->
                    <a
                        href="/si-akademik/public/mahasiswa/edit/<?= $mhs['id'] ?>"
                        class="btn btn-warning btn-sm"
                    >

                        Edit

                    </a>


                    <!-- DELETE -->
                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        onclick="hapusData(<?= $mhs['id'] ?>)"
                    >

                        Hapus

                    </button>

                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>


<script>

/*
 * Menampilkan notifikasi visual
 */
function tampilkanNotifikasi(pesan, tipe = 'success') {

    const notification =
        document.getElementById('notification');

    const notificationMessage =
        document.getElementById('notificationMessage');


    notification.className =
        `alert alert-${tipe} alert-dismissible fade show`;

    notificationMessage.textContent =
        pesan;


    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

}


/*
 * Menutup notifikasi
 */
function tutupNotifikasi() {

    const notification =
        document.getElementById('notification');

    notification.classList.add('d-none');

}


/*
 * DELETE MAHASISWA
 */
async function hapusData(id) {

    const yakin =
        confirm('Yakin ingin menghapus data ini?');


    if (!yakin) {
        return;
    }


    try {

        const response = await fetch(
            `/si-akademik/public/mahasiswa?id=${id}`,
            {
                method: 'DELETE',

                headers: {
                    'Authorization':
                        `Bearer ${localStorage.getItem('token')}`
                }
            }
        );


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


        let hasil;


        try {

            hasil =
                JSON.parse(responseText);

        } catch (error) {

            tampilkanNotifikasi(
                'Server mengirim response yang tidak valid.',
                'danger'
            );

            return;
        }


        /*
         * DELETE BERHASIL
         */
        if (response.ok) {

            tampilkanNotifikasi(
                hasil.message ||
                'Data mahasiswa berhasil dihapus.',
                'success'
            );


            /*
             * Reload setelah 1 detik
             * agar notifikasi sempat terlihat.
             */
            setTimeout(function() {

                window.location.reload();

            }, 1000);

        }


        /*
         * DELETE GAGAL
         */
        else {

            tampilkanNotifikasi(
                hasil.message ||
                'Data mahasiswa gagal dihapus.',
                'danger'
            );

        }


    } catch (error) {

        console.error(
            'Error:',
            error
        );


        tampilkanNotifikasi(
            'Terjadi kesalahan saat menghubungi server.',
            'danger'
        );

    }

}

</script>


<?php

$content = ob_get_clean();

include __DIR__ . '/../layouts/main.php';

?>