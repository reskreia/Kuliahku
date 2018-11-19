<?php

namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Akademik;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\KategoriMakul;
use App\Models\Progdi;
use App\Dosen;
use App\User;

class AkademikController extends Controller {

    public function cekNim($nim) {

        $getNim = Akademik::where('nim', '=', $nim)->first();

        if (empty($getNim)) {
            
          return response()->json(['error' => 'nim_tidak_terdaftar', 'message' => 'Maaf, nim tidak terdaftar.'], 500);
        }
        else {
            
			$cekUser = User::where('nim', '=', $getNim->nim)->first();

	        if (empty($cekUser)) {
	            
	          return response()->json($getNim);
	        }
	        else {
	            
          		return response()->json(['error' => 'nim_sudah_terdaftar', 'message' => 'Nim sudah terdaftar.'], 500);
	        }
        }
    }

    public function ambilProgdi($fakultas)
    {

        return Progdi::where('progdis.fakultas_id', $fakultas)
        	->join('jurusans', 'progdis.jurusan_id', '=', 'jurusans.id')
        	->select('progdis.jurusan_id as value', 'jurusans.nama_jur as label')
        	->get()
        	->toArray();
    }

    public function ambilWali($fakultas)
    {

        return Dosen::where('dosens.fakultas', $fakultas)
            ->select('dosens.id as value', 'dosens.name as label')
            ->get()
            ->toArray();
    }

    public function ambilFakultas()
    {

        return Fakultas::select('id as value', 'nama as label')->get()->toArray();
    }
    public function ambilJurusan()
    {

        return Jurusan::select('id as value', 'nama_jur as label')->get()->toArray();
    }
    public function ambilKategori()
    {

        return KategoriMakul::select('id as value', 'nama as label')->get()->toArray();
    }
}
