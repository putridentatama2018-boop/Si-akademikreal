<?php

namespace App\Entities;

class Mahasiswa
{
    private string $nim;
    private string $nama;

    public function __construct(string $nim, string $nama)
    {
        $this->setNim($nim);
        $this->setNama($nama);
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function setNim(string $nim): void
    {
        if (!ctype_digit($nim)) {
            throw new \InvalidArgumentException('NIM harus berupa angka.');
        }

        $this->nim = $nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        if (trim($nama) === '') {
            throw new \InvalidArgumentException('Nama mahasiswa tidak boleh kosong.');
        }

        $this->nama = $nama;
    }
}