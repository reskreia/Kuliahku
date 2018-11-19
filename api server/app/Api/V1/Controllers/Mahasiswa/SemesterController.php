<?php
namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Semester;
use App\Models\KrsData;
use JWTAuth;
use DB;
use Response;

class SemesterController extends Controller
{

	public function listSmsKhs()
	{
        $user = JWTAuth::parseToken()->authenticate();
        $result_nim = Semester::where('nim', '=', $user->nim)->first();

        if (empty($result_nim)) {
          return response()->json(['error' => 'invalid_credentials', 'message' => 'Data tidak ditemukan.'], 500);
        }
        else {
          return Semester::where('semesters.nim', '=', $user->nim)->where('semesters.cuti', '=', 0)
            ->select('semesters.id as value', DB::raw('CONCAT(semesters.nama, " ", semesters.angka) AS label'))
            ->orderBy('semesters.created_at', 'asc')
            ->get()->toArray();
        }
	}

    public function belumValid()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return Semester::where('semesters.nim', '=', $user->nim)
            ->select('semesters.id as value', DB::raw('CONCAT(semesters.nama, " ", semesters.angka) AS label'), 'semesters.keterangan')
            ->orderBy('semesters.created_at', 'asc')
            ->get()
            ->toArray();
    }

    public function detailSemester($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $idSms = Semester::where('nim', '=', $user->nim)->findOrFail($id);

        return KrsData::where('krs_datas.nim', '=', $user->nim)
            ->where('krs_datas.semester_id', '=', $idSms->id)
            ->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
            ->select('krs_datas.created_at', 'makuls.kode_mk as kodeMkl', 'makuls.nama_mk as namaMkl', 'krs_datas.status', DB::raw('CONCAT(makuls.sks, " ", makuls.sks_nama) AS sks'))
            ->orderBy('makuls.kode_mk', 'asc')
            ->get()->toArray();
    }

	public function addSemester(Request $request)
	{
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request, [
            'sms' => 'required'
        ]);

        $cutiCek = $request->get('cuti');

        $result_nim = Semester::where('nim', '=', $user->nim)
            ->where('angka', '=', $request->get('sms'))
            ->first();

        if (empty($result_nim)) {
            if ($cutiCek === true) {
                $itemt = new Semester;
                $itemt->angka = $request->sms;
                $itemt->keterangan = $request->keterangan;
                $itemt->cuti = 1;
                $itemt->nim = $user->nim;
                $itemt->save();  
            }
            else {
                $itemf = new Semester;
                $itemf->angka = $request->get('sms');
                $itemf->keterangan = $request->get('keterangan');
                $itemf->cuti = 0;
                $itemf->nim = $user->nim;
                $itemf->save();
            }
        }
        else {
          return response()->json(['error' => 'data_sudah_ada', 'message' => 'Data semester sudah ada.'], 500);
        }
	}

    public function delSemester($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $ceksms = Semester::where('nim', '=', $user->nim)->latest()->first();
        $deldata = Semester::where('nim', '=', $user->nim)->where('id', '=', $ceksms->id)->find($id);

        if (empty($deldata)) {
          return response()->json(['error' => 'tidak_dizinkan_menghapus', 'message' => 'Tidak bisa menghapus semester, hapus semester terbaru dahulu.'], 500);
        }
        else {
          
          if($deldata->delete()) {
            return response()->json(['status' => 'success', 'message' => 'krs berhasil di hapus.'], 200);
          }
        }
    }
}