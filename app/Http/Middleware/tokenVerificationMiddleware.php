<?php

namespace App\Http\Middleware;

use App\Helper\jwt_token;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class tokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('token');
        $result = jwt_token::verify($token);

        if ($result == "unauthorized") {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',
            ], 401);
        } else {
            $request->headers->set('email', $result);
            return $next($result);
        }
       
    }
}
