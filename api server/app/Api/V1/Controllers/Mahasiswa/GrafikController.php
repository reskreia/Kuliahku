<?php
namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\KrsUtama;
use App\Models\KrsData;
use App\Models\KhsNilai;
use JWTAuth;
use DB;
use Response;

class GrafikController extends Controller
{

	public function grapKrs() {
        $user = JWTAuth::parseToken()->authenticate();

        $label = DB::table("krs_datas")->where('krs_datas.nim', '=', $user->nim)
                ->join('semesters', 'krs_datas.semester_id', '=', 'semesters.id')
                ->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
                ->select(DB::raw('CONCAT(semesters.nama, " ", semesters.angka) AS nama'))
                ->groupBy('krs_datas.semester_id')
                ->pluck('nama');

        $sks = DB::table("krs_datas")->where('krs_datas.nim', '=', $user->nim)
                ->join('semesters', 'krs_datas.semester_id', '=', 'semesters.id')
                ->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
                ->select(DB::raw("sum(makuls.sks) as sks"))
                ->groupBy('krs_datas.semester_id')
                ->pluck('sks');

        return response()->json([
            "labels" => $label,
            "rows" => $sks
        ]);
	}

	public function grapKhs() {
        $user = JWTAuth::parseToken()->authenticate();

        $ips = DB::table("krs_datas")->where('krs_datas.nim', '=', $user->nim)
                ->join('nilais', 'krs_datas.nilai', '=', 'nilais.id')
                ->select(DB::raw("sum(nilais.angka) / count(krs_datas.id) as nilai"))
                ->orderBy('krs_datas.semester_id')
                ->groupBy('krs_datas.semester_id')
                ->get();

        $ipsnama = DB::table("krs_datas")->where('krs_datas.nim', '=', $user->nim)
                ->join('semesters', 'krs_datas.semester_id', '=', 'semesters.id')
                ->select(DB::raw('CONCAT(semesters.nama, " ", semesters.angka) AS nama'))
                ->orderBy('krs_datas.semester_id')
                ->groupBy('krs_datas.semester_id')
                ->pluck('nama');

        foreach ($ips as $q){
            $q->nilai = round($q->nilai, 2);
        }

        $hasil = $ips->pluck('nilai');

        return response()->json([
            "nsms" => $ipsnama,
            "isms" => $hasil

        ]);
	}
    public function totalKhs() {
        $user = JWTAuth::parseToken()->authenticate();
        
        $nilai = DB::table("krs_utamas")->where('krs_utamas.nim', '=', $user->nim)
                ->join('nilais', 'krs_utamas.nilai', '=', 'nilais.id')
                ->select(DB::raw("sum(nilais.angka) / count(krs_utamas.id) as nilai"))
                ->first();
        
        $hasil = round($nilai->nilai, 2);
        
        $total = 4;
        $hitung = ($hasil / $total) * 100;
        $persen = round($hitung, 2);
        
        return response()->json([
            "ipk" => $hasil,
            "persen" => $persen
        ]);
    }    
    public function nRata() {
        $user = JWTAuth::parseToken()->authenticate();
        
        $label = DB::table("krs_utamas")->where('krs_utamas.nim', $user->nim)
                ->join('nilais', 'krs_utamas.nilai', '=', 'nilais.id')
                ->select('nilais.huruf as nama')
                ->orderBy('nilais.id', 'desc')
                ->groupBy('krs_utamas.nilai')
                ->pluck('nama');

        $angka = DB::table("krs_utamas")->where('krs_utamas.nim', $user->nim)
                ->join('nilais', 'krs_utamas.nilai', '=', 'nilais.id')
                ->select(DB::raw("count(krs_utamas.id) as nilai"))
                ->orderBy('krs_utamas.nilai', 'desc')
                ->groupBy('krs_utamas.nilai')
                ->pluck('nilai');
        
        return response()->json([
            "label" => $label,
            "angka" => $angka
        ]);
    }
    
    public function totalSks() {
        $user = JWTAuth::parseToken()->authenticate();
        
        $sks = DB::table("krs_utamas")->where('krs_utamas.nim', '=', $user->nim)
                ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
                ->select(DB::raw("makuls.sks as sks"))
                ->sum('sks');

        $total = 145;

        $hitung = ($sks / $total) * 100;
        $hasil = round($hitung, 2);
        
        $krs = $sks * 1;
        
        return response()->json([
            "total" => $hasil,
            "krs" => $krs
        ]);
    }
}