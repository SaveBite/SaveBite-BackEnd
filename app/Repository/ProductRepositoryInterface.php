<?php

namespace App\Repository;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getProducts(int $perPage, array $columns = ['*'], array $relations = []);
}
