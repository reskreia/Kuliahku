<?php

namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\KrsUtama;

use App\Models\Semester;
use App\Models\KrsData;

use JWTAuth;
use DB;
use Response;

class KhsDataController extends Controller {

	public function listSms() {

        $user = JWTAuth::parseToken()->authenticate();
        $khsbelum = KhsNilai::where('nim', '=', $user->nim)->pluck('semester_id');

        return Semester::where('semesters.nim', '=', $user->nim)->whereNotIn('semesters.id', $khsbelum)
            ->select('semesters.id as value', DB::raw('CONCAT(semesters.nama, " ", semesters.angka) AS label'))
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();
	}

	public function ambilKS() {

        $user = JWTAuth::parseToken()->authenticate();

        return KhsNilai::where('khs_nilais.nim', '=', $user->nim)
            ->join('semesters', 'khs_nilais.semester_id', '=', 'semesters.id')
            ->select('khs_nilais.id as id', DB::raw('CONCAT(semesters.nama, " ", semesters.angka) AS sms'), 'khs_nilais.nilai as nilai')
            ->orderBy('khs_nilais.created_at', 'asc')
            ->get()
            ->toArray();
	}

	/** public function contohnotwhere($id) {
        
        $user = JWTAuth::parseToken()->authenticate();
        $krsbelum = KrsData::where('nim', '=', $user->nim)->pluck('krsUtama_id');

        return KrsUtama::where('krs_utamas.nim', '=', $user->nim)
            ->whereNotIn('krs_utamas.id', $krsbelum)
            ->join('semesters', 'krs_utamas.semester_id', '=', 'semesters.id')
            ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
            ->select('krs_utamas.id as value as value', 'makuls.nama_mk as label', DB::raw('CONCAT(semesters.nama, " ", semesters.angka) AS sublabel'), DB::raw('CONCAT(makuls.sks, " ", makuls.sks_nama) AS stamp'))
            ->orderBy('krs_utamas.created_at', 'desc')
            ->get()
            ->toArray();
	} **/

    public function listKrs($id) {
        
        $user = JWTAuth::parseToken()->authenticate();

        return KrsData::where('krs_datas.nim', '=', $user->nim)
            ->where('krs_datas.semester_id', '=', $id)
            ->join('semesters', 'krs_datas.semester_id', '=', 'semesters.id')
            ->join('makuls', 'krs_datas.makul_id', '=', 'makuls.id')
            ->join('nilais', 'krs_datas.nilai', '=', 'nilais.id')
            ->select('nilais.id as idNilai', 'nilais.huruf', 'krs_datas.semester_id as semester', 'krs_datas.id as idKrs', 'krs_datas.makul_id as idMakul', 'makuls.kode_mk as kodeMk', 'makuls.nama_mk as namaMk')
            ->orderBy('krs_datas.created_at', 'asc')
            ->get()
            ->toArray();
    }

	public function ulangKhs() {

        $user = JWTAuth::parseToken()->authenticate();

        $data = KrsUtama::where('krs_utamas.nim', $user->nim)
            ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
            ->join('kategori_makuls', 'makuls.kategori', '=', 'kategori_makuls.id')
            ->join('nilais', 'krs_utamas.nilai', '=', 'nilais.id')
            ->select('kategori_makuls.id', 'kategori_makuls.nama as kategori', 'nilais.id as nilai', 'nilais.huruf', 'makuls.nama_mk as namaMkl', DB::raw('CONCAT(makuls.sks, " ", makuls.sks_nama) AS sks'))
            ->orderBy('kategori_makuls.id', 'asc')
            ->orderBy('makuls.kode_mk', 'asc')
            ->get();

        // Decode your JSON and create a placeholder array
        $objects = json_decode($data);
        $grouped = array();
        // Loop JSON objects
        foreach($objects as $object) {
            if(!array_key_exists($object->id, $grouped)) { // a new ID...
                 $newObject = new \stdClass();

                 // Copy the ID/ID_NAME, and create an ITEMS placeholder
                 $newObject->id = $object->id;
                 $newObject->kategori = $object->kategori;
                 $newObject->makul = array();

                 // Save this new object
                 $grouped[$object->id] = $newObject;
            }

            $taskObject = new \stdClass();

            // Copy the TASK/TASK_NAME
            $taskObject->idkrs = $object->id;
            $taskObject->namaMk = $object->namaMkl;
            $taskObject->label = $object->namaMkl;
            $taskObject->nilai = $object->nilai;
            $taskObject->huruf = $object->huruf;
            $taskObject->sks = $object->sks;

            // Append this new task to the ITEMS array
            $grouped[$object->id]->makul[] = $taskObject;
        }

        // We use array_values() to remove the keys used to identify similar objects
        // And then re-encode this data :)
        $grouped = array_values($grouped);
        $json = json_encode($grouped);

        return response()->json($json);
	}
    
    public function khsNilai(Request $request, $id) {

        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request, [
          'makulId' => 'required',
          'nilai' => 'required'
        ]);

        $mk = $request->makulId;
        $resetNilai = $request->nreset;
        $n = $request->nilai;

        $getKhs = KrsData::where('id', $id)->first();

        $krsutama = KrsUtama::where('nim', $user->nim)
                ->where('makul_id', $mk)->first();


        if ($resetNilai === true) {

            $getKhs->nilai = $n;
            $getKhs->save();

            $krsutama->nilai = $n;
            $krsutama->save();

            return response()->json(['status' => 'success', 'message' => 'berhasil memperbarui nilai.'], 200);
        }
        elseif ($resetNilai === false){

            $getKhs->nilai = $n;
            $getKhs->save();

            $m = 5;

            if ($n > $m) {

                return response()->json(['error' => 'nilai_kebesaran', 'message' => 'nilai melebihi yang di harapkan.'], 500); 
            }  
            elseif($n > $krsutama->nilai) {

                $krsutama->nilai = $n;
                $krsutama->save();
            }

            return response()->json(['status' => 'success', 'message' => 'berhasil memperbarui nilai.'], 200);
        }
    }
}