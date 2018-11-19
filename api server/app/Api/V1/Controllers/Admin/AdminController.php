<?php

namespace App\Api\V1\Controllers\Admin;

use Symfony\Component\HttpKernel\Exception\HttpException;
use JWTAuth;
use App\Mimin;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use Config;

class AdminController extends Controller
{
    public function __construct()
    {
        Config::set('jwt.user', 'App\Mimin'); 
        Config::set('auth.providers.users.model', \App\Mimin::class);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function akun()
    {

        $user = JWTAuth::parseToken()->authenticate();

        return response()->json($user);
    }
}
