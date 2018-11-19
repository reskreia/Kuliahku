<?php

namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Makul;
use App\Models\Jurusan;
use App\Models\KrsData;
use JWTAuth;
use DB;
use Response;

class MakulController extends Controller
{

	public function pickKrs()
	{
        $user = JWTAuth::parseToken()->authenticate();

        $dMakul = Makul::where('jurusan', '=', $user->progdi)
            ->join('status_makuls', 'makuls.status', '=', 'status_makuls.id')
            ->select('makuls.id as value', 'makuls.nama_mk as label', 'makuls.semester', 'status_makuls.wp as sublabel', DB::raw('CONCAT(makuls.sks, " ", makuls.sks_nama) AS stamp'))
            ->orderBy('makuls.id', 'asc')
            ->get();
            
        foreach ($dMakul as $m) {
            $m->sublabel = 'Semester'.' '.$m->semester.', Status: '.$m->sublabel;
        }


        return $dMakul->toArray();
	}

    public function sdhKrs()
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        $krsbelum = KrsData::where('nim', '=', $user->nim)
            ->where('nilai', '!=', 1)
            ->where('nilai', '!=', 2)
            ->select('makul_id')
            ->groupBy('makul_id')
            ->pluck('makul_id');

        $data = Makul::whereNotIn('makuls.id', $krsbelum)
            ->where('makuls.jurusan', '=', $user->progdi)
            ->join('kategori_makuls', 'makuls.status', '=', 'kategori_makuls.id')
            ->join('jurusans', 'makuls.jurusan', '=', 'jurusans.id')
            ->select('makuls.semester as id', 'makuls.id as idMk', 'makuls.nama_mk as namaMk', 'makuls.kode_mk as kodeMk', 'makuls.sks', 'makuls.teori', 'makuls.status', 'makuls.prasyarat', 'makuls.praktek')
            ->orderBy('makuls.semester', 'asc')
            ->orderBy('makuls.status', 'asc')
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
                 $newObject->makul = array();

                 // Save this new object
                 $grouped[$object->id] = $newObject;
            }

            $taskObject = new \stdClass();

            // Copy the TASK/TASK_NAME
            $taskObject->mk = $object->idMk;
            $taskObject->kodeMk = $object->kodeMk;
            $taskObject->namaMk = $object->namaMk;
            $taskObject->sks = $object->sks;
            $taskObject->teori = $object->teori;
            $taskObject->praktek = $object->praktek;
            $taskObject->prasyarat = $object->prasyarat;
            $taskObject->status = $object->status;

            // Append this new task to the ITEMS array
            $grouped[$object->id]->makul[] = $taskObject;
        }

        // We use array_values() to remove the keys used to identify similar objects
        // And then re-encode this data :)
        $grouped = array_values($grouped);
        $json = json_encode($grouped);

        return response()->json($json);
    }

    public function pedKrs()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $data = Makul::where('makuls.jurusan', '=', $user->progdi)
            ->join('kategori_makuls', 'makuls.status', '=', 'kategori_makuls.id')
            ->join('jurusans', 'makuls.jurusan', '=', 'jurusans.id')
            ->select('makuls.semester as id', 'makuls.id as idMk', 'makuls.nama_mk as namaMk', 'makuls.kode_mk as kodeMk', 'makuls.sks', 'makuls.teori', 'makuls.status', 'makuls.prasyarat', 'makuls.praktek')
            ->orderBy('makuls.semester', 'asc')
            ->orderBy('makuls.status', 'asc')
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
                 $newObject->makul = array();

                 // Save this new object
                 $grouped[$object->id] = $newObject;
            }

            $taskObject = new \stdClass();

            // Copy the TASK/TASK_NAME
            $taskObject->mk = $object->idMk;
            $taskObject->kodeMk = $object->kodeMk;
            $taskObject->namaMk = $object->namaMk;
            $taskObject->sks = $object->sks;
            $taskObject->teori = $object->teori;
            $taskObject->praktek = $object->praktek;
            $taskObject->prasyarat = $object->prasyarat;
            $taskObject->status = $object->status;

            // Append this new task to the ITEMS array
            $grouped[$object->id]->makul[] = $taskObject;
        }

        // We use array_values() to remove the keys used to identify similar objects
        // And then re-encode this data :)
        $grouped = array_values($grouped);
        $json = json_encode($grouped);

        return response()->json($json);
    }
}
