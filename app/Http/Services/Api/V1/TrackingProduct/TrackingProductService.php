<?php

namespace App\Http\Services\Api\V1\TrackingProduct;

use App\Http\Resources\V1\TrackingProduct\TrackingProductCollection;
use App\Http\Resources\V1\TrackingProduct\TrackingProductResource;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Repository\TrackingProductRepositoryInterface;

abstract class TrackingProductService extends PlatformService
{
    use Responser;

    public function __construct(private readonly TrackingProductRepositoryInterface $repository){}

    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $trackingProducts = $this->repository->getTrackingProducts($perPage);
        return $this->responseSuccess(data: $trackingProducts);
    }

    public function show($id)
    {
        $trackingProduct = $this->repository->getById($id);
        return $this->responseSuccess(data: new TrackingProductResource($trackingProduct));
    }

    public function store($request)
    {
        $data = $request->validated();
        $trackingProduct = $this->repository->create($data);
        return $this->responseSuccess(data: new TrackingProductResource($trackingProduct));
    }

    public function update($request, $id)
    {
        $data = $request->validated();
        $updated = $this->repository->update($id, $data);
        if ($updated) {
            $trackingProduct = $this->repository->getById($id);
            return $this->responseSuccess(data: new TrackingProductResource($trackingProduct));
        }
        return $this->responseFail(message: 'Failed to update tracking product');
    }

    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);
        if ($deleted) {
            return $this->responseSuccess(message: 'Tracking product deleted successfully');
        }
        return $this->responseFail(message: 'Failed to delete tracking product');
    }
}
