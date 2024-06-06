<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\StoreRequest;
use App\Http\Requests\Api\Product\UpdateRequest;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * product repository
     *
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * index
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): JsonResponse
    {
        $products = $this->productRepository->getList([
            'key_word' => $request->get('s'),
            'per_page' => $request->get('per_page', 10),
            'page' => $request->get('page', 1),
            'user_id' => auth()->user()->id
        ]);

        return Response::success(__('Successfully!'), [
            'items' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * product details
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function show(int $id): JsonResponse
    {
        $store = $this->productRepository->get([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ]);

        return Response::success(__('Successfully!'), $store);
    }

    /**
     * store
     * 
     * @param Request $request
     *
     * @return Response
     */
    public function store(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $product = $this->productRepository->create([
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'price' => $request->get('price'),
                'description' => $request->get('description'),
                'store_id' => $request->get('store_id'),
            ]);

            DB::commit();
            return Response::success(__('Successfully!'), $product, 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return Response::error(__('Error!'), []);
        }
    }

    /**
     * update
     * 
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $product = $this->productRepository->update($id, [
                'name' => $request->get('name'),
                'slug' => $request->get('slug'),
                'price' => $request->get('price'),
                'description' => $request->get('description'),
                'store_id' => $request->get('store_id'),
            ]);

            DB::commit();
            return Response::success(__('Successfully!'), $product, 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return Response::error(__('Error!'), []);
        }
    }

    /**
     * delete
     * 
     * @param int $id
     *
     * @return Response
     */
    public function delete(int $id): JsonResponse
    {
        $deleted = $this->productRepository->delete($id);

        if ($deleted) {
            return Response::success(__('Successfully!'));
        } else {
            return Response::error(__('Error!'));
        }
    }
}