<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('authorization') === null) {
            throw new UnauthorizedException('token is not found');
        } else {

            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                // Handle expired token
                throw new UnauthorizedException('Token has expired');
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                // Handle invalid token
                throw new UnauthorizedException('Invalid token');
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                // Handle other JWT exceptions
                throw new UnauthorizedException('JWTException: ' . $e->getMessage());
            }

            // If the token is valid, proceed with the request
            return $next($request);

            // $user = JWTAuth::parseToken()->authenticate();

            // return $next($request);
        }
    }
}
