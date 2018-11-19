<?php

namespace App\Api\V1\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Biodata;
use App\User;
use JWTAuth;
use DB;
use Response;
use Illuminate\Support\Facades\Hash;

class BiodataController extends Controller {

    public function updatePassword(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        $this->validate($request, [
           'oldPass'     => 'required',
           'newPass'     => 'required'
        ]);
 
        $putUser = User::findOrFail($user->id);

        if(!Hash::check($request->oldPass, $putUser->password)){

            return response()->json(['status' => 'error', 'message' => 'The specified password does not match the database password' ], 500);

        }
        else{

            $putUser->password = $request->newPass;

            if(!$putUser->save()) {

                throw new HttpException(500);
            }
            else {

                return response()->json(['status' => 'success', 'message' => 'password changed.' ], 200);
            }
        }
    }

	public function info() {
        
        $user = JWTAuth::parseToken()->authenticate();
        
        $akun = User::where('users.nim', '=', $user->nim)
            ->join('jurusans', 'users.progdi', '=', 'jurusans.id')
            ->select('users.nim as nim', 'users.name as nama', 'users.email', 'jurusans.nama_jur as jurusan')
            ->get();
        
        $bio = Biodata::where('nim', '=', $user->nim)
            ->select('biodatas.id', 'biodatas.kelas', 'biodatas.asal', 'biodatas.hp', 'biodatas.lahir', 'biodatas.kelamin', 'biodatas.bekerja', 'biodatas.keterangan')
            ->get();
        
        return response()->json([
            "akun" => $akun,
            "biodata" => $bio
        ]);
	}

    public function dataBio(Request $request) {
        
        $user = JWTAuth::parseToken()->authenticate();
        
        $getBio = Biodata::where('nim', '=', $user->nim)->first();
        
        if (empty($getBio)) {
            
            $this->bioBaru();
        }
        else {
            
            $this->editBio();
        }
    }
    
    public function bioBaru() {
        
        $user = JWTAuth::parseToken()->authenticate();
        
        $date = Input::get('lahir');
        $fixed = date('Y-m-d', strtotime($date));       
        
        $nbio = new Biodata;
        $nbio->kelas = Input::get('kelas');
        $nbio->asal = Input::get('asal');
        $nbio->lahir = $fixed;
        $nbio->keterangan = Input::get('keterangan');
        $nbio->hp = Input::get('hp');
        $nbio->kelamin = Input::get('kelamin');
        $nbio->bekerja = Input::get('bekerja');
        $nbio->nim = $user->nim;
        $nbio->save();
        
        return $nbio;
    }    

    public function editBio() {
        
        $user = JWTAuth::parseToken()->authenticate();
        $id = Input::get('id');

        $date = Input::get('lahir');
        $fixed = date('Y-m-d', strtotime($date));           
        
        $ebio = Biodata::where('nim', '=', $user->nim)->findOrFail($id);
        $ebio->kelas = Input::get('kelas');
        $ebio->asal = Input::get('asal');
        $ebio->lahir = $fixed;
        $ebio->keterangan = Input::get('keterangan');
        $ebio->hp = Input::get('hp');
        $ebio->kelamin = Input::get('kelamin');
        $ebio->bekerja = Input::get('bekerja');
        $ebio->save();
        
        return $ebio;
    }
}
