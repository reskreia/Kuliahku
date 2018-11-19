<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dosen;
use App\Models\Fakultas;
use App\Mimin;
use Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserDosenController extends Controller
{

    public function __construct()
    {
        Config::set('jwt.user', 'App\Mimin'); 
        Config::set('auth.providers.users.model', \App\Mimin::class);
    }

    public function index()
    {
    	$user = JWTAuth::parseToken()->authenticate();

        return Dosen::join('fakultas', 'dosens.fakultas', '=', 'fakultas.id')
                ->select('dosens.id', 'fakultas.id as fid', 'dosens.nip', 'dosens.name as nama', 'dosens.email', 'fakultas.nama as fakultas')->get()->toArray();
    }

    public function update(Request $request,$id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request, [
            'nip' => 'required',
            'email' => 'required',
            'nama' => 'required',
            'fakultas' => 'required'
        ]);

        if(!$request->password){

            $edita = Dosen::findOrFail($id);
            $edita->nip         = $request->nip;
            $edita->email       = $request->email;
            $edita->name        = $request->nama;
            $edita->fakultas    = $request->fakultas;
            $edita->save();
            
            return $edita;
        }
        else {

            $edita = Dosen::findOrFail($id);
            $edita->nip         = $request->nip;
            $edita->email       = $request->email;
            $edita->name        = $request->nama;
            $edita->fakultas    = $request->fakultas;
            $edita->password    = $request->password;
            $edita->save();

            return $edita;
        }
    }

    public function ambilFakultas()
    {
    	$user = JWTAuth::parseToken()->authenticate();

        return Fakultas::select('id as value', 'nama as label')->get()->toArray();
    }
    
}
