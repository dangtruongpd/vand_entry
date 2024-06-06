<?php

namespace App\Http\Middleware;

use App\Repositories\Product\ProductRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\Response as ApiResponse;

class ProductPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $productRepository = app(ProductRepositoryInterface::class);
        $product = $productRepository->find($request->route('id'));
        $stores = auth()->user()->stores()->get();

        if (!$stores->contains('id', $product->store_id)) {
            return ApiResponse::error(__('Unauthorized!'), [], 403);
        }

        return $next($request);
    }
}
