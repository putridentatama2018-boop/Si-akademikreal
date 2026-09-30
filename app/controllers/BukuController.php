<?php

namespace App\Controllers;

use App\Models\Buku;

class BukuController extends BaseController
{
    private Buku $model;

    public function __construct()
    {
        $this->model = new Buku();
    }

    public function index()
    {
        $buku = $this->model->all();

        $this->view('buku/index', [
            'buku' => $buku
        ]);
    }

    public function show($id)
    {
        $buku = $this->model->find((int) $id);

        if (!$buku) {
            die('Data buku tidak ditemukan.');
        }

        $this->view('buku/show', [
            'buku' => $buku
        ]);
    }
}