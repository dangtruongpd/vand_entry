<?php

namespace App\Repositories\Store;

interface StoreRepositoryInterface
{
    function getList(array $filters = [], array $options = []);
    function filter(array $filters = []);
}