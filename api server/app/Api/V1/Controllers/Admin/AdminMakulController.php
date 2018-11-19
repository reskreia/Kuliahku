<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Makul;
use JWTAuth;
use DB;
use Response;
use Config;

class AdminMakulController extends Controller
{
    public function __construct()
    {
        Config::set('jwt.user', 'App\Mimin'); 
        Config::set('auth.providers.users.model', \App\Mimin::class);
    }

	public function ambilMasterKrs()
	{
        $user = JWTAuth::parseToken()->authenticate();

        return Makul::join('status_makuls', 'makuls.status', '=', 'status_makuls.id')
            ->join('kategori_makuls', 'makuls.status', '=', 'kategori_makuls.id')
            ->join('jurusans', 'makuls.jurusan', '=', 'jurusans.id')
            ->select('makuls.id', 'makuls.jurusan as jurusan', 'makuls.status', 'makuls.kategori','makuls.nama_mk as namaMk', 'makuls.kode_mk as kodeMk', 'makuls.sks', 'makuls.teori', 'makuls.semester', 'makuls.prasyarat', 'makuls.kurikulum', 'makuls.praktek', 'jurusans.nama_jur as progdi')
            ->orderBy('makuls.jurusan', 'asc')
            ->get()
            ->toArray();
	}

    public function tambahMakul(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request, [
            'kode' => 'required',
            'nama' => 'required',
            'teori' => 'required',
            'praktek' => 'required',
            'progdi' => 'required',
            'kategori' => 'required',
            'status' => 'required',
            'semester' => 'required'
        ]);

        $addMakul = new Makul;
        $addMakul->jurusan = $request->get('progdi');
        $addMakul->semester = $request->get('semester');
        $addMakul->kode_mk = $request->get('kode');
        $addMakul->nama_mk = $request->get('nama');
        $addMakul->sks = $request->get('teori') + $request->get('praktek');
        $addMakul->teori = $request->get('teori');
        $addMakul->praktek = $request->get('praktek');
        $addMakul->status = $request->get('status');
        $addMakul->kategori = $request->get('kategori');
        $addMakul->prasyarat = $request->get('syarat');
        $addMakul->save();         
    }

    public function editMakul(Request $request, $id) {

        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request, [
            'kode' => 'required',
            'nama' => 'required',
            'teori' => 'required',
            'praktek' => 'required',
            'progdi' => 'required',
            'kategori' => 'required',
            'status' => 'required',
            'semester' => 'required'
        ]);

        $editMakul = Makul::findOrFail($id);

        $editMakul->jurusan = $request->get('progdi');
        $editMakul->semester = $request->get('semester');
        $editMakul->kode_mk = $request->get('kode');
        $editMakul->nama_mk = $request->get('nama');
        $editMakul->sks = $request->get('teori') + $request->get('praktek');
        $editMakul->teori = $request->get('teori');
        $editMakul->praktek = $request->get('praktek');
        $editMakul->status = $request->get('status');
        $editMakul->kategori = $request->get('kategori');
        $editMakul->prasyarat = $request->get('prasyarat');
        $editMakul->save();
    }

    public function ambilKrsKurikulum($jurusan, $kurikulum)
    {
        $user = JWTAuth::parseToken()->authenticate();

        return Makul::where('jurusan', $jurusan)
            ->where('makuls.kurikulum', $kurikulum)
            ->join('status_makuls', 'makuls.status', '=', 'status_makuls.id')
            ->join('kategori_makuls', 'makuls.status', '=', 'kategori_makuls.id')
            ->join('jurusans', 'makuls.jurusan', '=', 'jurusans.id')
            ->select('makuls.id', 'makuls.jurusan as jurusan', 'makuls.status', 'makuls.kategori','makuls.nama_mk as namaMk', 'makuls.kode_mk as kodeMk', 'makuls.sks', 'makuls.teori', 'makuls.semester', 'makuls.prasyarat', 'makuls.kurikulum', 'makuls.praktek', 'jurusans.nama_jur as progdi')
            ->orderBy('makuls.kategori', 'asc')
            ->get()
            ->toArray();
    }

    public function ambilKurikulum($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        return DB::table('makuls')->where('jurusan', $id)->distinct()->get(['kurikulum as value', 'kurikulum as label']);


        //->groupBy('kurikulum')
        //    ->select('kurikulum as value', 'kurikulum as label', 'jurusan as idJurusan')
            //->groupBy('kurikulum')
        //    ->get();
    }

    public function hapusMakul($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $deldata = Makul::findOrFail($id);
        $deldata->delete();
    }
}