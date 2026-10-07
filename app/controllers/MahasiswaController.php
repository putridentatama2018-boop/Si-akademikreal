<?php 
 
namespace App\Controllers; 
 
use App\Services\MahasiswaService; 
 
class MahasiswaController extends BaseController 
{ 
    private MahasiswaService $service; 
 
    public function __construct(MahasiswaService $service) 
    { 
        $this->service = $service; 
    } 
 
    // ===================================================== 
    // Menampilkan semua mahasiswa 
    // ===================================================== 
    public function index() 
    { 
        $keyword = $_GET['q'] ?? ''; 
 
        if ($keyword !== '') { 
            $mahasiswa = $this->service->search($keyword); 
        } else { 
            $mahasiswa = $this->service->all(); 
        } 
 
        $this->view('mahasiswa/index', [ 
            'mahasiswa' => $mahasiswa 
        ]); 
    } 
 
 
    // ===================================================== 
    // Form tambah mahasiswa 
    // ===================================================== 
    public function create() 
    { 
        $prodiModel = new \App\Models\ProdiModel(); 
 
        $prodi = $prodiModel->all(); 
 
        $this->view('mahasiswa/create', [ 
            'prodi' => $prodi 
        ]); 
    } 
 
 
    // ===================================================== 
    // Menambahkan mahasiswa 
    // ===================================================== 
    public function store() 
    { 
        $data = [ 
            'nim' => $_POST['nim'] ?? '', 
            'nama' => $_POST['nama'] ?? '', 
            'email' => $_POST['email'] ?? '', 
            'angkatan' => $_POST['angkatan'] ?? '', 
            'prodi_id' => $_POST['prodi_id'] ?? '', 
            'status' => $_POST['status'] ?? 'aktif' 
        ]; 
 
 
        // ================================================= 
        // Upload foto 
        // ================================================= 
        if ( 
            isset($_FILES['foto']) && 
            $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE 
        ) { 
 
            $file = $_FILES['foto']; 
 
 
            if ($file['error'] !== UPLOAD_ERR_OK) { 
 
                $_SESSION['flash'] = [ 
                    'type' => 'error', 
                    'message' => 'Foto gagal diupload.' 
                ]; 
 
                $this->redirect( 
                    '/si-akademik/public/mahasiswa/create' 
                ); 
 
                return; 
            } 
 
 
            $ekstensi = strtolower( 
                pathinfo($file['name'], PATHINFO_EXTENSION) 
            ); 
 
 
            if ( 
                !in_array( 
                    $ekstensi, 
                    ['jpg', 'jpeg', 'png'] 
                ) || 
                @exif_imagetype($file['tmp_name']) === false 
            ) { 
 
                $_SESSION['flash'] = [ 
                    'type' => 'error', 
                    'message' => 'Berkas harus berupa gambar JPG/PNG yang valid.' 
                ]; 
 
                $this->redirect( 
                    '/si-akademik/public/mahasiswa/create' 
                ); 
 
                return; 
            } 
 
 
            if ($file['size'] > 2 * 1024 * 1024) { 
 
                $_SESSION['flash'] = [ 
                    'type' => 'error', 
                    'message' => 'Ukuran foto maksimal 2MB.' 
                ]; 
 
                $this->redirect( 
                    '/si-akademik/public/mahasiswa/create' 
                ); 
 
                return; 
            } 
 
 
            $namaBaru = 
                uniqid('foto_', true) . 
                '.' . 
                $ekstensi; 
 
 
            $folderUpload = 
                __DIR__ . 
                '/../../public/uploads/'; 
 
 
            if (!is_dir($folderUpload)) { 
                mkdir($folderUpload, 0755, true); 
            } 
 
 
            if ( 
                !move_uploaded_file( 
                    $file['tmp_name'], 
                    $folderUpload . $namaBaru 
                ) 
            ) { 
 
                $_SESSION['flash'] = [ 
                    'type' => 'error', 
                    'message' => 'Foto gagal disimpan.' 
                ]; 
 
                $this->redirect( 
                    '/si-akademik/public/mahasiswa/create' 
                ); 
 
                return; 
            } 
 
 
            $data['foto'] = $namaBaru; 
 
        } else { 
 
            $data['foto'] = null; 
        } 
 
 
        // ================================================= 
        // Simpan data mahasiswa 
        // ================================================= 
        $result = $this->service->create($data); 
 
 
        if (!$result['success']) { 
 
            if (!empty($data['foto'])) { 
 
                $fileFoto = 
                    __DIR__ . 
                    '/../../public/uploads/' . 
                    $data['foto']; 
 
                if (file_exists($fileFoto)) { 
                    unlink($fileFoto); 
                } 
            } 
 
 
            $_SESSION['flash'] = [ 
                'type' => 'error', 
                'message' => implode( 
                    '<br>', 
                    $result['errors'] 
                ) 
            ]; 
 
 
            $this->redirect( 
                '/si-akademik/public/mahasiswa/create' 
            ); 
 
            return; 
        } 
 
 
        $_SESSION['flash'] = [ 
            'type' => 'success', 
            'message' => 'Data mahasiswa berhasil ditambahkan.' 
        ]; 
 
 
        $this->redirect( 
            '/si-akademik/public/mahasiswa' 
        ); 
    } 
 
 
    // ===================================================== 
    // Form edit mahasiswa 
    // ===================================================== 
    public function edit(int $id) 
    { 
        $mahasiswa = $this->service->find($id); 
 
 
        if (!$mahasiswa) { 
            die('Data mahasiswa tidak ditemukan.'); 
        } 
 
 
        $prodiModel = new \App\Models\ProdiModel(); 
 
        $prodi = $prodiModel->all(); 
 
 
        $this->view('mahasiswa/edit', [ 
            'mahasiswa' => $mahasiswa, 
            'prodi' => $prodi 
        ]); 
    } 
 
 
    // ===================================================== 
    // ACARA 27 
    // Mengubah mahasiswa menggunakan PUT + FormData 
    // PUT /mahasiswa?id=ID 
    // ===================================================== 
    public function update() 
    { 
        // ------------------------------------------------- 
        // Ambil ID dari query string 
        // ------------------------------------------------- 
        $id = (int) ( 
            $_GET['id'] ?? 
            $_POST['id'] ?? 
            0 
        ); 
 
 
        if ($id <= 0) { 
 
            $this->kirimResponseUpdate( 
                false, 
                'ID mahasiswa tidak valid.' 
            ); 
 
            return; 
        } 
 
 
        // ------------------------------------------------- 
        // Ambil data mahasiswa lama 
        // ------------------------------------------------- 
        $mahasiswaLama = $this->service->find($id); 
 
 
        if (!$mahasiswaLama) { 
 
            $this->kirimResponseUpdate( 
                false, 
                'Data mahasiswa tidak ditemukan.' 
            ); 
 
            return; 
        } 
 
 
        // ------------------------------------------------- 
        // Ambil data dari FormData 
        // ------------------------------------------------- 
        $data = [ 
            'nim' => $_POST['nim'] ?? '', 
            'nama' => $_POST['nama'] ?? '', 
            'email' => $_POST['email'] ?? '', 
            'angkatan' => $_POST['angkatan'] ?? '', 
            'prodi_id' => $_POST['prodi_id'] ?? '', 
            'status' => $_POST['status'] ?? 'aktif' 
        ]; 
 
 
        // ------------------------------------------------- 
        // Gunakan foto lama jika tidak ada foto baru 
        // ------------------------------------------------- 
        $data['foto'] = 
            $mahasiswaLama['foto'] ?? null; 
 
 
        $namaBaru = null; 
 
 
        // ================================================= 
        // Upload foto baru 
        // ================================================= 
        if ( 
            isset($_FILES['foto']) && 
            $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE 
        ) { 
 
            $file = $_FILES['foto']; 
 
 
            // --------------------------------------------- 
            // Cek error upload 
            // --------------------------------------------- 
            if ($file['error'] !== UPLOAD_ERR_OK) { 
 
                $this->kirimResponseUpdate( 
                    false, 
                    'Foto gagal diupload.' 
                ); 
 
                return; 
            } 
 
 
            // --------------------------------------------- 
            // Cek ekstensi 
            // --------------------------------------------- 
            $ekstensi = strtolower( 
                pathinfo( 
                    $file['name'], 
                    PATHINFO_EXTENSION 
                ) 
            ); 
 
 
            // --------------------------------------------- 
            // Cek tipe gambar 
            // --------------------------------------------- 
            if ( 
                !in_array( 
                    $ekstensi, 
                    ['jpg', 'jpeg', 'png'] 
                ) || 
                @exif_imagetype( 
                    $file['tmp_name'] 
                ) === false 
            ) { 
 
                $this->kirimResponseUpdate( 
                    false, 
                    'Berkas harus berupa gambar JPG/PNG yang valid.' 
                ); 
 
                return; 
            } 
 
 
            // --------------------------------------------- 
            // Cek ukuran foto 
            // --------------------------------------------- 
            if ($file['size'] > 2 * 1024 * 1024) { 
 
                $this->kirimResponseUpdate( 
                    false, 
                    'Ukuran foto maksimal 2MB.' 
                ); 
 
                return; 
            } 
 
 
            // --------------------------------------------- 
            // Buat nama file baru 
            // --------------------------------------------- 
            $namaBaru = 
                uniqid('foto_', true) . 
                '.' . 
                $ekstensi; 
 
 
            $folderUpload = 
                __DIR__ . 
                '/../../public/uploads/'; 
 
 
            if (!is_dir($folderUpload)) { 
                mkdir( 
                    $folderUpload, 
                    0755, 
                    true 
                ); 
            } 
 
 
            // --------------------------------------------- 
            // Simpan file 
            // --------------------------------------------- 
            if ( 
                !move_uploaded_file( 
                    $file['tmp_name'], 
                    $folderUpload . $namaBaru 
                ) 
            ) { 
 
                $this->kirimResponseUpdate( 
                    false, 
                    'Foto gagal disimpan.' 
                ); 
 
                return; 
            } 
 
 
            $data['foto'] = $namaBaru; 
        } 
 
 
        // ================================================= 
        // Update database 
        // ================================================= 
        $result = 
            $this->service->update( 
                $id, 
                $data 
            ); 
 
 
        // ================================================= 
        // Jika update gagal 
        // ================================================= 
        if (!$result['success']) { 
 
            // Hapus foto baru jika database gagal diupdate 
            if (!empty($namaBaru)) { 
 
                $fileFotoBaru = 
                    __DIR__ . 
                    '/../../public/uploads/' . 
                    $namaBaru; 
 
 
                if (file_exists($fileFotoBaru)) { 
                    unlink($fileFotoBaru); 
                } 
            } 
 
 
            $pesanError = implode( 
                '<br>', 
                $result['errors'] 
            ); 
 
 
            $this->kirimResponseUpdate( 
                false, 
                $pesanError 
            ); 
 
            return; 
        } 
 
 
        // ================================================= 
        // Hapus foto lama jika diganti 
        // ================================================= 
        if ( 
            !empty($namaBaru) && 
            !empty($mahasiswaLama['foto']) 
        ) { 
 
            $fileFotoLama = 
                __DIR__ . 
                '/../../public/uploads/' . 
                $mahasiswaLama['foto']; 
 
 
            if (file_exists($fileFotoLama)) { 
                unlink($fileFotoLama); 
            } 
        } 
 
 
        // ================================================= 
        // Berhasil 
        // ================================================= 
        $this->kirimResponseUpdate( 
            true, 
            'Data mahasiswa berhasil diupdate.' 
        ); 
    } 
 
 
    // ===================================================== 
    // Response untuk proses Update Acara 27 
    // ===================================================== 
    private function kirimResponseUpdate( 
        bool $success, 
        string $message 
    ): void { 
 
        // ------------------------------------------------- 
        // Jika request menggunakan PUT dari frontend 
        // maka kirim JSON 
        // ------------------------------------------------- 
        if ( 
            ($_SERVER['REQUEST_METHOD'] ?? 'GET') 
            === 'PUT' 
        ) { 
 
            header( 
                'Content-Type: application/json; charset=utf-8' 
            ); 
 
 
            http_response_code( 
                $success ? 200 : 422 
            ); 
 
 
            echo json_encode( 
                [ 
                    'success' => $success, 
                    'message' => $message 
                ], 
                JSON_UNESCAPED_UNICODE 
            ); 
 
 
            return; 
        } 
 
 
        // ------------------------------------------------- 
        // Jika masih menggunakan POST dari form lama 
        // tetap menggunakan flash + redirect 
        // ------------------------------------------------- 
        $_SESSION['flash'] = [ 
            'type' => $success 
                ? 'success' 
                : 'error', 
            'message' => $message 
        ]; 
 
 
        if ($success) { 
 
            $this->redirect( 
                '/si-akademik/public/mahasiswa' 
            ); 
 
        } else { 
 
            $id = (int) ( 
                $_GET['id'] ?? 
                $_POST['id'] ?? 
                0 
            ); 
 
 
            $this->redirect( 
                '/si-akademik/public/mahasiswa/edit/' . 
                $id 
            ); 
        } 
    } 
 
 
    // ===================================================== 
    // Menghapus mahasiswa melalui route GET lama
    // ===================================================== 
    public function delete(int $id) 
    { 
        // Ambil data mahasiswa sebelum dihapus 
        $mahasiswa = $this->service->find($id); 
 
 
        $result = 
            $this->service->delete($id); 
 
 
        if ($result) { 
 
            // --------------------------------------------- 
            // Hapus file foto jika ada 
            // --------------------------------------------- 
            if ( 
                $mahasiswa && 
                !empty($mahasiswa['foto']) 
            ) { 
 
                $fileFoto = 
                    __DIR__ . 
                    '/../../public/uploads/' . 
                    $mahasiswa['foto']; 
 
 
                if (file_exists($fileFoto)) { 
                    unlink($fileFoto); 
                } 
            } 
 
 
            $_SESSION['flash'] = [ 
                'type' => 'success', 
                'message' => 'Data mahasiswa berhasil dihapus.' 
            ]; 
 
        } else { 
 
            $_SESSION['flash'] = [ 
                'type' => 'error', 
                'message' => 'Data mahasiswa gagal dihapus.' 
            ]; 
        } 
 
 
        $this->redirect( 
            '/si-akademik/public/mahasiswa' 
        ); 
    }


    // =====================================================
    // ACARA 27
    // Menghapus mahasiswa menggunakan DELETE
    // DELETE /mahasiswa?id=ID
    // =====================================================
    public function destroy(): void
    {
        // -------------------------------------------------
        // Ambil ID dari query string
        // -------------------------------------------------
        $id = (int) ($_GET['id'] ?? 0);


        // -------------------------------------------------
        // Cek ID
        // -------------------------------------------------
        if ($id <= 0) {

            header(
                'Content-Type: application/json; charset=utf-8'
            );

            http_response_code(422);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'ID mahasiswa tidak valid.'
                ],
                JSON_UNESCAPED_UNICODE
            );

            return;
        }


        // -------------------------------------------------
        // Cari data mahasiswa sebelum dihapus
        // -------------------------------------------------
        $mahasiswa = $this->service->find($id);


        if (!$mahasiswa) {

            header(
                'Content-Type: application/json; charset=utf-8'
            );

            http_response_code(404);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan.'
                ],
                JSON_UNESCAPED_UNICODE
            );

            return;
        }


        // -------------------------------------------------
        // Hapus data mahasiswa dari database
        // -------------------------------------------------
        $result = $this->service->delete($id);


        // -------------------------------------------------
        // Jika gagal
        // -------------------------------------------------
        if (!$result) {

            header(
                'Content-Type: application/json; charset=utf-8'
            );

            http_response_code(500);

            echo json_encode(
                [
                    'success' => false,
                    'message' => 'Data mahasiswa gagal dihapus.'
                ],
                JSON_UNESCAPED_UNICODE
            );

            return;
        }


        // -------------------------------------------------
        // Hapus foto mahasiswa jika ada
        // -------------------------------------------------
        if (!empty($mahasiswa['foto'])) {

            $fileFoto =
                __DIR__ .
                '/../../public/uploads/' .
                $mahasiswa['foto'];


            if (file_exists($fileFoto)) {
                unlink($fileFoto);
            }
        }


        // -------------------------------------------------
        // Response berhasil
        // -------------------------------------------------
        header(
            'Content-Type: application/json; charset=utf-8'
        );

        http_response_code(200);

        echo json_encode(
            [
                'success' => true,
                'message' => 'Data mahasiswa berhasil dihapus.'
            ],
            JSON_UNESCAPED_UNICODE
        );
    }
}