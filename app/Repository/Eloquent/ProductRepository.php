<?php

namespace App\Repository\Eloquent;

use App\Models\Product;
use App\Repository\Eloquent\Repository;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends Repository implements ProductRepositoryInterface
{
    protected Model $model;

    public function __construct(Product $model){
        parent::__construct($model);
    }

    public function getProducts(int $perPage, array $columns = ['*'], array $relations = [])
    {
        return $this->model->query()
            ->when(request()->filled('search'), function($query){
                $serchItem = "%" . request()->search . "%";
                $columns = ['product name', 'category', 'price', 'quantity' , 'reorder level','reorder quantity', 'units sold' , 'sales value'];
                $query->where(function($query) use ($serchItem, $columns){
                    foreach ($columns as $column){
                        $query->orWhere($column, 'like', $serchItem);
                    }
                });
            })
            ->with($relations)
            ->select($columns)
            ->paginate($perPage);
    }
}
