<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Mimin;
use App\Models\Akademik;
use App\Models\Makul;
use Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Excel;
use File;
use DB;
use Response;

class UserMahasiswaController extends Controller
{

    public function __construct()
    {
        Config::set('jwt.user', 'App\Mimin'); 
        Config::set('auth.providers.users.model', \App\Mimin::class);
    }

    public function index()
    {
    	$user = JWTAuth::parseToken()->authenticate();

        $siswa = User::join('fakultas', 'users.fakultas', '=', 'fakultas.id')
                ->join('jurusans', 'users.progdi', '=', 'jurusans.id')
                ->join('krs_utamas', 'users.nim', '=', 'krs_utamas.nim')
                ->join('makuls', 'krs_utamas.makul_id', '=', 'makuls.id')
                ->select(
                    'users.id',
                    'users.fakultas as fid', 
                    'users.progdi as pid',
                    'users.nim',
                    'users.name as nama',
                    'users.email',
                    'fakultas.nama as fakultas',
                    'jurusans.nama_jur as progdi',
                    DB::raw("count(krs_utamas.id) as makul"),
                    DB::raw("sum(makuls.sks) as sks")
                )
                ->groupBy('users.nim')
                ->get();

        foreach($siswa as $m) {

            $sms = DB::table('semesters')->where('nim', $m->nim)
                ->latest()->first();

            $makul = Makul::where('jurusan', $m->pid)->count();

            $m->makul = $m->makul.' dari '.$makul;
            $m->sms   = $sms->angka;
        }

        $data = $siswa->sortBy('makul');


        $res = [];
        foreach ($data  as $key => $value) {
            $res[] = $value;
        }
        
        return Response::json($res); 
    }

    public function listNim()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return DB::table('akademiks')->select('nim')->get()->toArray();
    }

    public function update(Request $request,$id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request, [
            'nim' => 'required',
            'email' => 'required',
            'name' => 'required',
            'fakultas' => 'required',
            'progdi' => 'required'
        ]);

        if(!$request->password){

            $edita = User::findOrFail($id);
            $edita->nim = $request->nim;
            $edita->email = $request->email;
            $edita->name = $request->name;
            $edita->fakultas = $request->fakultas;
            $edita->progdi = $request->progdi;
            $edita->save();
            
            return $edita;
        }
        else {

            $edita = User::findOrFail($id);
            $edita->nim = $request->nim;
            $edita->email = $request->email;
            $edita->name = $request->name;
            $edita->fakultas = $request->fakultas;
            $edita->progdi = $request->progdi;
            $edita->password = $request->password;
            $edita->save();

            return $edita;
        }
    }

    public function importFile(Request $request){
        //validate the xls file
        $this->validate($request, array(
            'file'      => 'required'
        ));
 
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){
 
                    foreach ($data as $key => $value) {
                        $insert[] = [
                        'nim' => $value->nim,
                        ];
                    }
 
                    if(!empty($insert)){
 
                        $insertData = Akademik::insert($insert);
                        if ($insertData) {

                            return response()->json(['status' => 'success', 'message' => 'data added.'], 200);
                        }else {            

                            return response()->json(['error' => 'tidak_ada_data', 'message' => 'Tidak ada data.'], 500);
                        }
                    }
                }
 
                return back();
 
            }else {

                $mes = 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!';
                return response()->json(['status' => 'error', 'message' => $mes], 500);
            }
        }
    }

}
