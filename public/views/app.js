const BASE_URL = 'http://localhost/si-akademik/public';

async function muatDataMahasiswa() {

    const loading = document.querySelector('#loading');
    const error = document.querySelector('#error');
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    try {

        const response = await fetch(
            'http://localhost/si-akademik/public/api/mahasiswa'
        );

        if (!response.ok) {
            throw new Error(
                `HTTP Error ${response.status}`
            );
        }

        const hasil = await response.json();

        if (!hasil.success) {
            throw new Error(
                hasil.message || 'Gagal mengambil data'
            );
        }

        tbody.innerHTML = '';

        if (!hasil.data || hasil.data.length === 0) {

            tbody.innerHTML = `
                <tr>
                    <td colspan="3">Belum ada data mahasiswa</td>
                </tr>
            `;

        } else {

            hasil.data.forEach((mhs) => {

                tbody.innerHTML += `
                    <tr>
                        <td>${mhs.nim}</td>
                        <td>${mhs.nama}</td>
                        <td>${mhs.nama_prodi ?? '-'}</td>
                    </tr>
                `;

            });
        }

        loading.style.display = 'none';

    } catch (error) {

        loading.style.display = 'none';

        errorElement = document.querySelector('#error');

        errorElement.textContent =
            'Gagal memuat data: ' + error.message;

        console.error(error);
    }
}

document.addEventListener(
    'DOMContentLoaded',
    muatDataMahasiswa
);