<?php

namespace App\Http\Middleware;

use Closure;

class HeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('api_key') != env('API_KEY')) {
            return response()->json([
                'message' => 'Invalid Request'
            ], 500);
        }

        $response = $next($request);
        
        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Content-Range, Content-Disposition, Content-Description, X-Auth-Token');
        $response->header('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
