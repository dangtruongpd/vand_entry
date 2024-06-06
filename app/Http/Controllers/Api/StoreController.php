<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Store\StoreRequest;
use App\Http\Requests\Api\Store\UpdateRequest;
use App\Repositories\Store\StoreRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * store repository
     *
     * @var StoreRepositoryInterface
     */
    private StoreRepositoryInterface $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
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
        $stores = $this->storeRepository->getList([
            'key_word' => $request->get('s'),
            'per_page' => $request->get('per_page', 10),
            'page' => $request->get('page', 1),
            'user_id' => auth()->user()->id
        ]);

        return Response::success(__('Successfully!'), [
            'items' => $stores->items(),
            'pagination' => [
                'current_page' => $stores->currentPage(),
                'per_page' => $stores->perPage(),
                'total' => $stores->total(),
            ]
        ]);
    }

    /**
     * store details
     * 
     * @param int $id
     *
     * @return Response
     */
    public function show(int $id): JsonResponse
    {
        $store = $this->storeRepository->get([
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
            $store = $this->storeRepository->create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'user_id' => auth()->user()->id,
            ]);

            DB::commit();
            return Response::success(__('Successfully!'), $store, 201);
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
            $store = $this->storeRepository->update($id, [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ]);

            DB::commit();
            return Response::success(__('Successfully!'), $store, 200);
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
        $deleted = $this->storeRepository->delete($id);

        if ($deleted) {
            return Response::success(__('Successfully!'));
        } else {
            return Response::error(__('Error!'));
        }
    }
}