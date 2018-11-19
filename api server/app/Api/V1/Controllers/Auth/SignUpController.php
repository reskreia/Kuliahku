<?php

namespace App\Api\V1\Controllers\Auth;

use Config;
use App\User;
use App\Models\Akademik;
use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use App\Api\V1\Requests\SignUpRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SignUpController extends Controller
{

    public function register(SignUpRequest $request, JWTAuth $JWTAuth) {

        // cek nim sudah ada atau belum, jika sudah ada register ke sistem
        // jika ada tambahkan user
        $user_mail = User::where('email', '=', $request->get('email'))->first();
        $user_nim = User::where('nim', '=', $request->get('nim'))->first();
        $result_nim = Akademik::where('nim', '=', $request->get('nim'))->first();
        if (empty($result_nim)) {
           return response()->json(['error' => 'invalid_credentials', 'message' => 'Maaf, nim tidak terdaftar.'], 500);
        }
        elseif(isset($user_nim) > 0) {
             return response()->json(['error' => 'invalid_credentials', 'message' => 'Nim sudah terdaftar.'], 401);         
        }
        elseif(isset($user_mail) > 0) {
             return response()->json(['error' => 'invalid_credentials', 'message' => 'Email sudah terdaftar.'], 401);         
        }
        elseif(empty($user_mail) && empty($user_nim) && isset($result_nim) > 0) {

            $user = new User($request->all());
            if(!$user->save()) {
                throw new HttpException(500);
            }

            $token = $JWTAuth->fromUser($user);
            
            if(!Config::get('boilerplate.sign_up.release_token')) {
                return response()->json([
                    'status' => 'ok',
                    'token' => $token
                ], 201);
            }

            return response()->json([
                'status' => 'ok',
                'token' => $token
            ], 201);

        }
    }
}
