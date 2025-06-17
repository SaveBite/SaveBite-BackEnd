<?php

namespace App\Repository\Eloquent;

use App\Models\TrackingProduct;
use App\Repository\Eloquent\Repository;
use App\Repository\TrackingProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class TrackingProductRepository extends Repository implements TrackingProductRepositoryInterface
{
    protected Model $model;

    public function __construct(TrackingProduct $model){
        parent::__construct($model);
    }

    public function getTrackingProducts(int $perPage, array $columns = ['*'], array $relations = [])
    {
        return $this->model->query()
            ->when(request()->filled('search'), function($query){
                $searchItem = "%" . request()->search . "%";
                $columns = ['name', 'numberId', 'category', 'quantity', 'label', 'status'];
                $query->where(function($query) use ($searchItem, $columns){
                    foreach ($columns as $column){
                        $query->orWhere($column, 'like', $searchItem);
                    }
                });
            })
            ->with($relations)
            ->select($columns)
            ->paginate($perPage);
    }
}
