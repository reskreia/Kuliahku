<?php

namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\KrsData;
use App\Models\Semester;
use App\Models\KrsUtama;
use JWTAuth;
use DB;
use Response;
use Carbon\Carbon;

class KrsDataController extends Controller
{

    public function listSemester()
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        return Semester::where('nim', $user->nim)->where('cuti', 0)
            ->select('id as value', DB::raw('CONCAT(nama, " ", angka) AS label'))
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->get()
            ->toArray();
    }  

	public function listKrs()
	{
        $user = JWTAuth::parseToken()->authenticate();
        $result_nim = KrsData::where('nim', $user->nim)->first();

        if (empty($result_nim)) {
          return response()->json(['error' => 'invalid_credentials', 'message' => 'Data tidak ditemukan.'], 500);
        }
        else { 
          return KrsData::where('krs_datas.nim', $user->nim)
            ->join('semesters', 'krs_datas.semester_id', '=', 'semesters.id')
            ->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
            ->select('krs_datas.id as krsId', 'makuls.id as makulId', 'krs_datas.created_at', 'semesters.angka as namaSms', 'semesters.id as smsId', 'makuls.kode_mk as kodeMkl', 'makuls.nama_mk as namaMkl', 'krs_datas.status')
            // ->whereDate('krs_datas.created_at', DB::raw('CURDATE()'))
            ->orderBy('krs_datas.created_at', 'desc')
            ->latest()->first();
        }     
        
	}

    public function newKRS(Request $request)
    {
        
        $this->validate($request, [
          'pickSemester' => 'required',
          'idKrs' => 'required'
        ]);  

        $user = JWTAuth::parseToken()->authenticate();


        $sms = $request->pickSemester;
        $krs = $request->idKrs;

        $ada = KrsData::where('semester_id', $request->pickSemester)
                ->whereIn('makul_id', $krs)
                ->where('nim', $user->nim)->first();
            
        if (isset($ada) > 0) {
                
                 return response()->json(['error' => 'invalid_credentials', 'message' => 'Apakah anda yakin mengambil dua makul bersamaan di semester.'], 401); 

        }
        else{

            foreach($krs as $k){

                $ada = KrsData::where('semester_id', $request->pickSemester)
                    ->where('makul_id', $k)
                    ->where('nim', $user->nim)->first();
                
                $getkrs = KrsUtama::where('makul_id', $k)
                    ->where('nim', $user->nim)->first();
                
                if (isset($ada) > 0) {
                    
                     return response()->json(['error' => 'invalid_credentials', 'message' => 'Apakah anda yakin mengambil dua makul bersamaan di semester.'], 401); 

                }

                if (empty($getkrs)) {
                    
                    $kb = new KrsData;
                    $kb->semester_id    = $sms;
                    $kb->makul_id       = $k;
                    $kb->status         = 'Baru';
                    $kb->nim            = $user->nim;

                    $kb->save();

                    $kut = new KrsUtama;
                    $kut->semester_id   = $sms;
                    $kut->makul_id      = $k;
                    $kut->nim           = $user->nim;

                    $kut->save();

                }
                elseif ($getkrs)  {
                    
                    $ku = new KrsData;
                    $ku->semester_id = $request->pickSemester;
                    $ku->makul_id = $k;
                    $ku->status = 'Mengulang';
                    $ku->nim = $user->nim;
                    $ku->save();

                }
            }

             return KrsData::where('krs_datas.nim', $user->nim)
                ->where('krs_datas.semester_id', $sms)
                ->join('semesters', 'krs_datas.semester_id', '=', 'semesters.id')
                ->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
                ->select('krs_datas.id as krsId', 'makuls.id as makulId', 'krs_datas.created_at', 'semesters.angka as namaSms', 'semesters.id as smsId', 'makuls.kode_mk as kodeMkl', 'makuls.nama_mk as namaMkl', 'krs_datas.status')
                ->whereDate('krs_datas.created_at', DB::raw('CURDATE()'))
                ->orderBy('krs_datas.created_at', 'desc')
                ->get();   
        }
    }

    public function krsBaru($sms, $krs)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $kb = new KrsData;
        $kb->semester_id    = $sms;
        $kb->makul_id       = $krs;
        $kb->status         = 'Baru';
        $kb->nim            = $user->nim;

        $kb->save();

        $kut = new KrsUtama;
        $kut->semester_id   = $sms;
        $kut->makul_id      = $krs;
        $kut->nim           = $user->nim;

        $kut->save();

    }
    public function delKrs(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $cek     = KrsData::where('nim', $user->nim)->where('makul_id', $request->makulId)->latest()->first();

        $deldata = KrsData::where('nim', $user->nim)->where('id', $request->krsId)->first();
        $delUtama = KrsUtama::where('nim', $user->nim)->where('semester_id', $request->smsId)->first();

        if ($delUtama->semester_id === $request->smsId && $deldata->semester_id === $request->smsId) {
          $deldata->delete();
          $delUtama->delete();
        }
        elseif ($deldata->semester_id === $request->smsId) {
          $deldata->delete();
        }
        else {
          return response()->json(['error' => 'tidak_dizinkan_menghapus', 'message' => 'Hapus yang baru terlebih dahulu.'], 500);

        }
    }
}
