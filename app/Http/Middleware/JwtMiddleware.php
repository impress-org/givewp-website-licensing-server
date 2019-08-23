<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JwtMiddleware
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @param  null  $guard
     *
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->get('token');

        if (! $token) {
            // Unauthorized response if token not there
            return response()->json(array(
                'msg' => 'Token not provided.',
            ), 401);
        }
        try {
            JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json(array(
                'msg' => 'Provided token is expired.',
            ), 400);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return response()->json(array(
                'msg' => 'An error while decoding token.',
            ), 400);
        }

        return $next($request);
    }
}
