<?php

namespace App\Repositories\Store;

use App\Repositories\BaseRepository;

class StoreRepository extends BaseRepository implements StoreRepositoryInterface
{
    /**
     * get model
     * 
     * @return string
     */
    public function getModel(): string
    {
        return 'App\Models\Store';
    }

    /**
     * get list item
     * 
     * @param array $filters
     * @param array $options
     * 
     * @return mixed
     */
    public function getList(array $filters = [], array $options = []): mixed
    {
        $this->filter($filters);

        if (!empty($filters['key_word'])) {
            $this->_model = $this->_model->where('stores.name', 'like', "%{$filters['key_word']}%");
        }

        return $this->_model->paginate($filters['per_page'], ['*'], 'page', $filters['page']);
    }

    /**
     * get item
     * 
     * @param array $filters
     * @param array $options
     * 
     * @return mixed
     */
    public function get(array $filters = [], array $options = []): mixed
    {
        $this->filter($filters);
        $this->_model = $this->_model->with('products');

        return $this->_model->first();
    }

    /**
     * filter
     * 
     * @param array $filters
     * 
     */
    public function filter(array $filters = []): void
    {
        if (!empty($filters['id'])) {
            $this->_model = $this->_model->where('stores.id', $filters['id']);
        }

        if (!empty($filters['user_id'])) {
            $this->_model = $this->_model->where('user_id', $filters['user_id']);
        }
    }
}