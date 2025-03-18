<?php

namespace App\Http\Services\Api\V1\Product;

use App\Http\Resources\V1\Analytics\AnalyticsResource;
use App\Http\Resources\V1\Analytics\SalesPredictionsResource;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Services\Mutual\CSVFileService;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Services\Mutual\GetService;
use App\Http\Services\Mutual\StockModelService;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Jobs\UpcomingReordersJob;
use App\Models\AnalyticsPredictions;
use App\Models\UpcomingReorder;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

abstract class ProductService extends PlatformService
{
    use Responser;
    public function __construct(private  readonly ProductRepositoryInterface $repository,
                                private readonly FileManagerService $fileManagerService,
                                private readonly StockModelService $stockModelService,
                                private readonly CSVFileService $csvFileService,
                                private readonly GetService $getService,
                                ){}


    public function upload($request)
    {
        $file = $this->fileManagerService->handle('csv_file', 'csv_files');
        $columns = ['Date','ProductName', 'Category', 'UnitPrice', 'StockQuantity' , 'ReorderLevel','ReorderQuantity', 'UnitsSold' , 'SalesValue', 'Month'];
        $check = $this->csvFileService->store($file, $columns , $this->repository);
        if ($check) {
//            $this->stockModelService->upload($file, UpcomingReorder::query());
            UpcomingReordersJob::dispatchAfterResponse($file);
            return $this->responseSuccess(message: __('messages.file_uploaded_successfully'));
        }else{
            return $this->responseFail(__('messages.Something went wrong'));
        }
    }

    public function index()
    {
        $user = auth('api')->user();
        $products = $user->products;
        return $this->responseSuccess(data: ProductCollection::make($products));
    }

    public function store($request)
    {
        $data = $request->validated();
        $data['user_id'] = auth('api')->id();
        $product = $this->repository->create($data);
        return $this->responseSuccess(data: new ProductResource($product));
    }

    public function stock()
    {
        $reorders = UpcomingReorder::where('user_id', auth('api')->id()) // Optional: filter by user
        ->when(request()->filled('search'), function($query){
            $query->where('ProductName', 'like', '%'.request()->input('search').'%');
        })
        ->when(request()->filled('category'), function($query){
            $query->where('Category', request()->input('category'));
        })
        ->orderBy('Date', 'ASC')
            ->get();

        $startDate = UpcomingReorder::query()->where('user_id', auth('api')->id())
            ->orderBy('Date', 'ASC')->pluck('Date')->first();
        $endDate = UpcomingReorder::query()->where('user_id', auth('api')->id())
            ->orderBy('Date', 'DESC')->pluck('Date')->first();
        $grouped = $reorders->groupBy('ProductName');

        // Format the response
        $data = $grouped->map(function ($items, $productName) {
            return [
                'ProductName' => $productName,
                'Category' => $items->first()->Category, // Take category from the first item
                'ReorderQuantities' => $items->pluck('ReorderQuantity')->toArray(), // Collect all reorder quantities
            ];
        })->values();

        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $data,
        ];

        return $this->responseSuccess(data: $data);
    }

    public function analytics()
    {
        return $this->getService->handle(AnalyticsResource::class, $this->repository, 'getAnalytics',is_instance: true);
    }

    public function salesPredictions()
    {
        $predictions = AnalyticsPredictions::where('user_id', auth('api')->id())->orderBy('Date', 'ASC')->get();
        $start_date = $predictions->first()->date;
        $end_date = $predictions->last()->date;
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'data' => SalesPredictionsResource::collection($predictions),
        ];
        return $this->responseSuccess(data: $data);
    }
}
