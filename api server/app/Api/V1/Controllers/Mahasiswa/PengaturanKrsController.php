<?php

namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\KrsUtama;
use App\Models\KrsData;
use App\Models\Makul;
use JWTAuth;
use DB;
use Response;

class PengaturanKrsController extends Controller
{
	public function cariKrs(Request $request)
	{
        $user = JWTAuth::parseToken()->authenticate();
        
        $krsnya = KrsUtama::where('nim', '=', $user->nim)
        ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
        ->where('makuls.status', '=', $request->get('dipilih'))
        ->select('makuls.id as makulId')
        ->pluck('makulId');

        if ($krsnya->isEmpty()) {
           return response()->json(['error' => 'tidak_ada_data', 'message' => 'Tidak ada data.'], 500);
        }
        else {

            $wajib = Makul::where('makuls.jurusan', '=', $user->progdi)
                ->where('makuls.status', '=', $request->get('dipilih'))
                ->whereNotIn('makuls.id', $krsnya)
                ->join('semesters', 'makuls.semester', '=', 'semesters.id')
                ->select('makuls.semester as sms', 'makuls.kode_mk as sublabel', 'makuls.nama_mk as label', 'makuls.sks as sks')
                ->orderBy('makuls.semester', 'asc')
                ->get();

            return response()->json($wajib);
        }
	}

    public function detailKrs() {

        $user = JWTAuth::parseToken()->authenticate();

        $krsnya = KrsUtama::where('nim', '=', $user->nim)
        ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
        ->where('makuls.status', '=', 1)
        ->count('makuls.id');

        $sksnya = KrsUtama::where('nim', '=', $user->nim)
        ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
        ->where('makuls.status', '=', 1)
        ->pluck('makuls.sks');
        $msks = $sksnya->sum();

        $wajib = Makul::where('makuls.jurusan', '=', $user->progdi)
                ->where('makuls.status', '=', 1)
                ->count('makuls.id');

        $sksttl = Makul::where('makuls.jurusan', '=', $user->progdi)
                ->where('makuls.status', '=', 1)
                ->pluck('makuls.sks');
        $total = $sksttl->sum();

        return response()->json([
                "amakul" => $wajib,
                "asks" => $total,
                "msks" => $msks,
                "mambil" => $krsnya
        ]);
    }

	public function cariUlang()
	{
        $user = JWTAuth::parseToken()->authenticate();

        $krsnya = KrsData::where('krs_datas.nim', '=', $user->nim)
                ->where('krs_datas.status', 'Mengulang')
                ->join('semesters', 'krs_datas.semester_id', '=', 'semesters.id')
                ->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
                ->select(DB::raw('CONCAT(semesters.nama, " ", semesters.angka) AS sms'), 'makuls.nama_mk as label', 'makuls.sks as sks')
                ->orderBy('semesters.id', 'asc')
                ->get();

        if (empty($krsnya)) {
           return response()->json(['error' => 'tidak_ada_data', 'message' => 'Tidak ada data.'], 500);
        }
        else {

            return $krsnya->toArray();
        }
	}
}