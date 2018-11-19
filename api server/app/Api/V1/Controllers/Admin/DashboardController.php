<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mimin;
use App\User;
use Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;
use Response;

class DashboardController extends Controller
{

    public function __construct() {
        Config::set('jwt.user', 'App\Mimin'); 
        Config::set('auth.providers.users.model', \App\Mimin::class);
    }

    public function fakultasChart() {
    	$user = JWTAuth::parseToken()->authenticate();

        $label = DB::table("users")
                ->join('jurusans', 'users.progdi', '=', 'jurusans.id')
                ->select('jurusans.nama_jur as progdi')
                ->orderBy('jurusans.id')
                ->groupBy('users.progdi')
                ->pluck('progdi');

        $sks = DB::table("users")
                ->join('jurusans', 'users.progdi', '=', 'jurusans.id')
                ->select(DB::raw("count(users.nim) as total"))
                ->orderBy('jurusans.id')
                ->groupBy('users.progdi')
                ->pluck('total');

        return response()->json([
            "labels" => $label,
            "rows" => $sks
        ]);
    }
}
