<?php

namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jurusan;

class JurusanController extends Controller
{
    public function index()
    {
        return Jurusan::select('nama_jur as label', 'jurusans.kode_jur as value')->get()
          ->toJson();
    }
}
