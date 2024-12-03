<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function _construct()
    {
        $this->middleware('api', ['except' => ['login']]);
    }

    public function login (Request $request) : \Illuminate\Http\JsonResponse {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
			'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 402);
        }

        if (!$token=auth()->attempt($validator->validated())) {
            return response()->json([
                "error" => "Unauthorized",
                401
            ]);
        }
        return $this->createNewToken($token);
    }

    public function createNewToken ($token) {
        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL()*60,
                'user' => auth()->user(),
            ],
            'message' => 'User Login Successfully',
            'success' => true,
        ]);
    }

    public function logout () : \Illuminate\Http\JsonResponse {
        auth()->logout();
        return response()->json([
            'data' => [],
            'message' => 'User Logout Successfully',
            'success' => true
        ]);
    }
}
