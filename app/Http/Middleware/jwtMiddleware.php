<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use \Firebase\JWT\JWT;
use Illuminate\Http\Exceptions\HttpResponseException;
use Firebase\JWT\Key;
use App\Models\User;
class jwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationHeader = explode(' ', $request->header('Authorization')); // ['Bearer', 'TOKEN']
        $head = isset($authorizationHeader[0]) ? $authorizationHeader[0] : false;
        $jwt = isset($authorizationHeader[1]) ? $authorizationHeader[1] : false;
        if (!$head || !$jwt) {
            throw new HttpResponseException(response()->json([
                'statusCode' => 404,

                'success'   => false,

                'message'   => 'geçersiz kullanıcı',

                'data'      => null

            ]));
        }

        try {
            $secretKey = env('JWT_SECRET');
            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
            $user = User::find($decoded->id);
            if($user->token !=$jwt ){
                throw new HttpResponseException(response()->json([
                    'statusCode' => 404,

                    'success'   => false,

                    'message'   => 'geçersiz kullanıcı',

                    'data'      => null

                ]));
            }
            $request->request->add(['user' => $user]);
            return $next($request);
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json([
                'statusCode' => 404,

                'success'   => false,

                'message'   => 'geçersiz kullanıcı',

                'data'      => $e->getMessage()

            ]));
        }
    }
}
