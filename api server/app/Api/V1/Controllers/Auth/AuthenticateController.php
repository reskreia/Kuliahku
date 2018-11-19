<?php
namespace App\Api\V1\Controllers\Auth;

use App\Api\V1\Transformers\UsersTransformer;
use App\Http\Requests;
use App\User;
use App\Models\Akademik;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Auth;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthenticateController extends Controller
{
    public function login(Request $request)
    {

        $field = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';
        $request->merge([$field => $request->input('login')]);

        $credentials = $request->only($field, 'password');
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials', 'message' => 'Oups! Terjadi kesalahan, coba ulangi.'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token', 'message' => 'Could not create token. Try again'], 500);
        }
        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    public function getAuthUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        // the token is valid and we have found the user via the sub claim
        return $this->item($user, new UsersTransformer);
    }

    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return $this->error('Token NOT provided!', 401);
        }
        $token = JWTAuth::refresh($token);
        return response()->json(compact('token'));
    }
}