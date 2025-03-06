<?php

namespace App\Http\Services\Api\V1\Product;

use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Services\Mutual\CSVFileService;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Services\Mutual\StockModelService;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Jobs\UpcomingReordersJob;
use App\Models\UpcomingReorder;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

abstract class ProductService extends PlatformService
{
    use Responser;
    public function __construct(private  readonly ProductRepositoryInterface $repository,
                                private readonly FileManagerService $fileManagerService,
                                private readonly StockModelService $stockModelService,
                                private readonly CSVFileService $csvFileService
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
        ->orderBy('Date', 'ASC')
            ->get();


        $grouped = $reorders->groupBy('ProductName');

        // Format the response
        $data = $grouped->map(function ($items, $productName) {
            return [
                'ProductName' => $productName,
                'Category' => $items->first()->Category, // Take category from the first item
                'ReorderQuantities' => $items->pluck('ReorderQuantity')->toArray(), // Collect all reorder quantities
            ];
        })->values();

        return $this->responseSuccess(data: $data);
    }
}
