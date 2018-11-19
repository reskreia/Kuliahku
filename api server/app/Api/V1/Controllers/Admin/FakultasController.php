<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Mimin;
use Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class FakultasController extends Controller
{

    public function __construct()
    {
        Config::set('jwt.user', 'App\Mimin'); 
        Config::set('auth.providers.users.model', \App\Mimin::class);
    }

    public function index()
    {
    	$user = JWTAuth::parseToken()->authenticate();

        return Fakultas::select('id', 'nama')->get()->toArray();
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
            'nama' => 'required'
        ]);

        $item = new Fakultas;
        $item->nama = $request->get('nama');
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
            'nama' => 'required'
        ]);

        $edita = Fakultas::findOrFail($id);
        $edita->nama = $request->get('nama');
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
    
}
