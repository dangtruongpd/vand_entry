<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\Response as ApiResponse;

class StorePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->stores()->get()->contains('id', $request->route('id'))) {
            return ApiResponse::error(__('Unauthorized!'), [], 403);
        }

        return $next($request);
    }
}
