<?php

namespace App\Repositories\Product;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * get model
     * 
     * @return string
     */
    public function getModel(): string
    {
        return 'App\Models\Product';
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
            $this->_model = $this->_model->where('products.name', 'like', "%{$filters['key_word']}%");
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
        $this->_model = $this->_model->with('store');

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
            $this->_model = $this->_model->where('products.id', $filters['id']);
        }

        if (!empty($filters['user_id'])) {
            $this->_model = $this->_model->select(['products.*']);
            $this->_model = $this->_model->join('stores', 'stores.id', 'products.store_id');
            $this->_model = $this->_model->join('users', 'users.id', 'stores.user_id');
            $this->_model = $this->_model->where('users.id', $filters['user_id']);
        }
    }
}