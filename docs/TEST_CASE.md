# TEST CASE MANDIRI ACARA 23 

| ID Test Case | Skenario Pengujian | Data Uji | Hasil yang Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| TC-07 | Update data mahasiswa dengan data valid | ID 4, NIM 4545678, Nama Silpi Update Acara 23, Email silpiupdate@gmail.com, Prodi 1, Angkatan 2029, Status aktif | Data mahasiswa berhasil diubah dan HTTP 200 | Data mahasiswa berhasil diubah dan HTTP 200 | PASS |
| TC-08 | Update data mahasiswa dengan nama kosong | ID 4, nama kosong (`""`) | Sistem menolak data dan menampilkan pesan validasi bahwa nama wajib diisi | Sistem menolak data dengan pesan "Validasi gagal" dan error "Nama wajib diisi" | PASS |
| TC-09 | Update data mahasiswa dengan ID yang tidak ditemukan | ID 99999 dengan data mahasiswa valid | Sistem menolak update dan menampilkan pesan data mahasiswa tidak ditemukan | Sistem menolak update dengan pesan "Data mahasiswa tidak ditemukan" | PASS |
| TC-10 | Delete data mahasiswa dengan ID yang valid | ID 36, NIM 2700023, Nama Mahasiswa Test Delete | Data mahasiswa berhasil dihapus dan sistem mengembalikan HTTP 200 | Data mahasiswa berhasil dihapus dan HTTP 200 | PASS |
| TC-11 | Delete data mahasiswa dengan ID yang tidak ditemukan | ID 99999 | Sistem menolak penghapusan dan menampilkan pesan data mahasiswa tidak ditemukan | Sistem menolak penghapusan dengan pesan "Data mahasiswa tidak ditemukan" | PASS |