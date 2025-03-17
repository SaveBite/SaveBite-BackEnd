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

    public function getAnalytics()
    {
        $currentStartDate = now()->subDays(28);
        $lastMonthStartDate = now()->subDays(56);
        $lastMonthEndDate = now()->subDays(29);
        $stockTurnoverRate = $this->model->query()
            ->where(function ($query) use ($currentStartDate) {
                $query->where('user_id', auth('api')->id())
                    ->whereDate('Date', '<=', $currentStartDate);
            })
            ->selectRaw('SUM(UnitsSold) / NULLIF(SUM(StockQuantity),0) as stockTurnoverRate')
            ->value('stockTurnoverRate');

        $previousStockTurnoverRate = $this->model->query()
            ->where('user_id', auth('api')->id())
            ->whereBetween('Date', [$lastMonthStartDate, $lastMonthEndDate])
            ->selectRaw('SUM(UnitsSold) / NULLIF(SUM(StockQuantity), 0) as stockTurnoverRate')
            ->value('stockTurnoverRate');
        $stockTurnoverRateChange = $this->calculateChangeRate($stockTurnoverRate, $previousStockTurnoverRate);

        $reorderAccuracyRate = $this->model->query()
            ->where(function ($query) use ($currentStartDate) {
                $query->where('user_id', auth('api')->id())
                    ->whereDate('Date', '<=', $currentStartDate);
            })
            ->selectRaw('1 - ABS((SUM(ReorderQuantity) - SUM(UnitsSold)) / NULLIF(SUM(UnitsSold), 0)) as reorderAccuracyRate')
            ->value('reorderAccuracyRate');
        $previousReorderAccuracyRate = $this->model->query()
            ->where('user_id', auth('api')->id())
            ->whereBetween('Date', [$lastMonthStartDate, $lastMonthEndDate])
            ->selectRaw('1 - ABS((SUM(ReorderQuantity) - SUM(UnitsSold)) / NULLIF(SUM(UnitsSold), 0)) as reorderAccuracyRate')
            ->value('reorderAccuracyRate');
        $reorderAccuracyRateChange = $this->calculateChangeRate($reorderAccuracyRate, $previousReorderAccuracyRate);

        $categoryOverstocking = $this->model->query()
            ->where(function ($query) use ($currentStartDate) {
                $query->where('user_id', auth('api')->id())
                    ->whereDate('Date', '<=', $currentStartDate);
            })
            ->groupBy('Category')
            ->selectRaw('Category, SUM(StockQuantity) - SUM(UnitsSold) as overstock')
            ->pluck('overstock','Category');

        $previousCategoryOverstocking = $this->model->query()
            ->where('user_id', auth('api')->id())
            ->whereBetween('Date', [$lastMonthStartDate, $lastMonthEndDate])
            ->groupBy('Category')
            ->selectRaw('Category, SUM(StockQuantity) - SUM(UnitsSold) as overstock')
            ->pluck('overstock', 'Category');
        $categoryOverstockingChange = [];
        foreach ($categoryOverstocking as $category => $value) {
            $previousValue = $categoryOverstockingChange[$category] ?? 0;
            $categoryOverstockingChange[$category] = $this->calculateChangeRate($value, $previousValue);
        }

        $revenue = $this->model->query()
            ->where('user_id', auth('api')->id())
            ->whereDate('Date', '<=', $currentStartDate)
            ->sum('SalesValue');
        $previousRevenue = $this->model->query()
            ->where('user_id', auth('api')->id())
            ->whereBetween('Date', [$lastMonthStartDate, $lastMonthEndDate])
            ->sum('SalesValue');
        $revenueChange = $this->calculateChangeRate($revenue, $previousRevenue);

        $data = [
            "stock_turnover_rate" => $stockTurnoverRate,
            "stock_turnover_rate_change" => $stockTurnoverRateChange,
            "reorder_accuracy_rate" => $reorderAccuracyRate,
            "reorder_accuracy_rate_change" => $reorderAccuracyRateChange,
            "category_overstocking" => $categoryOverstocking,
            "category_overstocking_change" => $categoryOverstockingChange,
            "revenue" => $revenue,
            "revenue_change" => $revenueChange,
        ];

        return $data;
    }

    protected function calculateChangeRate($current, $previous)
    {
        return $previous != 0 ? ($current - $previous) / $current * 100 : 0;
    }
}
