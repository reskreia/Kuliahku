<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Mimin;
use Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JurusanController extends Controller
{

    public function __construct()
    {
        Config::set('jwt.user', 'App\Mimin'); 
        Config::set('auth.providers.users.model', \App\Mimin::class);
    }

    public function index()
    {
    	$user = JWTAuth::parseToken()->authenticate();

        return Jurusan::select('id', 'nama_jur as nama')->get()->toArray();
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
            'nama_jur' => 'required'
        ]);

        $item = new Jurusan;
        $item->nama_jur = $request->get('nama_jur');
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
            'nama_jur' => 'required'
        ]);

        $edita = Jurusan::findOrFail($id);
        $edita->nama_jur = $request->get('nama_jur');
        $edita->save();
        
        return $edita;
	}
}
