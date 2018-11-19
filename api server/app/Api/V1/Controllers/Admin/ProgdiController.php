<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Progdi;
use App\Mimin;
use Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProgdiController extends Controller
{

    public function __construct()
    {
        Config::set('jwt.user', 'App\Mimin'); 
        Config::set('auth.providers.users.model', \App\Mimin::class);
    }

    public function index()
    {
    	$user = JWTAuth::parseToken()->authenticate();

        return Progdi::join('fakultas', 'progdis.fakultas_id', '=', 'fakultas.id')
        	->join('jurusans', 'progdis.jurusan_id', '=', 'jurusans.id')
        	->select('progdis.id', 'progdis.jurusan_id as jid', 'progdis.fakultas_id as fid', 'fakultas.nama as fakultas', 'jurusans.id as idJurusan','jurusans.nama_jur as jurusan')->get()->toArray();
    }
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request, [
            'jurusan_id' => 'required',
            'fakultas_id' => 'required'
        ]);

        $item = new Progdi;
        $item->jurusan_id = $request->get('jurusan_id');
        $item->fakultas_id = $request->get('fakultas_id');
        $item->save();

        return $item;
	}	

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,$id)
	{
        $user = JWTAuth::parseToken()->authenticate();

        $this->validate($request, [
            'jurusan_id' => 'required',
            'fakultas_id' => 'required'
        ]);

        $edita = Progdi::findOrFail($id);
        $edita->jurusan_id = $request->get('jurusan_id');
        $edita->fakultas_id = $request->get('fakultas_id');
        $edita->save();
        
        return $edita;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

	} 
 
    public function ambilJurusan()
    {
    	$user = JWTAuth::parseToken()->authenticate();

        return Jurusan::select('id as value', 'nama_jur as label')->get()->toArray();
    }

    public function ambilFakultas()
    {
    	$user = JWTAuth::parseToken()->authenticate();

        return Fakultas::select('id as value', 'nama as label')->get()->toArray();
    }
}
