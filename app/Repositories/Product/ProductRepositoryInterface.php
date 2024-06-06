<?php

namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    function getList(array $filters = [], array $options = []);
    function filter(array $filters = []);
}