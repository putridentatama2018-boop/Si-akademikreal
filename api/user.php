?php
 
public function create(array $data): string {
    try {
        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, prodi) VALUES (:nim, :nama, :prodi)'
        );
        $stmt->execute([
            ':nim'   => $data['nim'],
            ':nama'  => $data['nama'],
            ':prodi' => $data['prodi'],
        ]);
        return $this->db->lastInsertId();
    } catch (PDOException $e) {
        Response::json(500, false, 'Terjadi kesalahan pada server');
    }
}
 
