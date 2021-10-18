<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class apiProtectedRoute extends BaseMiddleware
{
//    private JWTAuth $jwt;
//    public function __construct(JWTAuth $auth)
//    {
//        parent::__construct($auth);
//        $this->jwt = $auth;
//    }


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $exception) {
            if ($exception instanceof TokenInvalidException) {
                return send_error('Token inválido', '', 422);
            } else if ($exception instanceof TokenExpiredException) {
                return send_error('Token expirado', '', 422);
            } else {
                return send_error('Token não encontrado', '', 404);
            }
        }
        return $next($request);
    }
}

