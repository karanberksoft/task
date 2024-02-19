<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthLoginRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use \Firebase\JWT\JWT;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function Register(AuthRegisterRequest $request)
    {
        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->token = '';
            $user->refreshToken = '';
            $user->status = $request->status;

            $user->save();

            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'message' => 'kayıt başarılı',
                'data' => null
            ]);
        } catch (\Exception $e) {
            Log::warning($request, $e->getMessage());
            throw new HttpResponseException(response()->json([
                'statusCode' => 500,

                'success'   => false,

                'message'   => 'Validation errors',

                'data'      => $e->getMessage()

            ]));
        }
    }
    public function Login(AuthLoginRequest $request)
    {
        try {

            $user = User::where('email', $request->email)->first();
            if ($user == null) {
                return response()->json([
                    'statusCode' => 404,

                    'success'   => false,

                    'message'   => 'ilgili maile kayıtlı hesap bulunamadı',

                    'data'      => null

                ]);
            }


            $jwt = JWT::encode([
                'id' => $user->id,
                'iat' => time(),
                "exp" => time() + 60 * 60 * 1 # 1 saatlik bir jwt oluşturuyoruz.
            ], env('JWT_SECRET'), 'HS256');
            $refresh_jwt = JWT::encode([
                'id' => $user->id,
                'iat' => time(),
                "exp" => time() + 60 * 60 * 24 # 24 saatlik bir jwt oluşturuyoruz.
            ], env('JWT_SECRET'), 'HS256');

            $user->token = $jwt;
            $user->refreshToken = $refresh_jwt;
            $user->save();
            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'message' => 'giriş başarılı',
                'data' => ['token' => $jwt, 'refresh_token' => $refresh_jwt]
            ]);
        } catch (\Exception $e) {
            Log::warning($request, $e->getMessage());
            throw new HttpResponseException(response()->json([
                'statusCode' => 500,

                'success'   => false,

                'message'   => 'Validation errors',

                'data'      => $e->getMessage()

            ]));
        }
    }
    public function UserDetail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            return response()->json([
                'statusCode' => 404,

                'success'   => false,

                'message'   => 'ilgili maile kayıtlı hesap bulunamadı',

                'data'      => null

            ]);
        }
        return response()->json([
            'statusCode' => 200,
            'success' => true,
            'message' => 'giriş başarılı',
            'data' => $request->user
        ]);
    }

    public function RefreshToken(Request $request)
    {

        try {
            $user = User::where('refreshToken', $request->refreshToken)->first();

            $jwt = JWT::encode([
                'id' => $user->id,
                'iat' => time(),
                "exp" => time() + 60 * 60 * 1 # 1 saatlik bir jwt oluşturuyoruz.
            ], env('JWT_SECRET'), 'HS256');
            $user->token = $jwt;
            $user->save();
            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'message' => 'giriş başarılı',
                'data' => ['token' => $user->token, 'refreshToken' => $user->refreshToken]
            ]);
        } catch (\Exception $e) {
            Log::warning($request, $e->getMessage());
            throw new HttpResponseException(response()->json([
                'statusCode' => 500,

                'success'   => false,

                'message'   => 'Validation errors',

                'data'      => $e->getMessage()

            ]));
        }
    }
    public function Logout(Request $request)
    {

        try {
            $user = User::where('id', $request->user->id)->first();
            $user->token = '';
            $user->refreshToken = '';
            $user->save();
            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'message' => 'çıkış başarılı',
                'data' => null
            ]);
        } catch (\Exception $e) {
            Log::warning($request, $e->getMessage());
            throw new HttpResponseException(response()->json([
                'statusCode' => 500,

                'success'   => false,

                'message'   => 'Validation errors',

                'data'      => $e->getMessage()

            ]));
        }
    }
}
