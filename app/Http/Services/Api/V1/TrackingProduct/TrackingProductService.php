<?php

namespace App\Http\Services\Api\V1\TrackingProduct;

use App\Http\Resources\V1\TrackingProduct\TrackingProductCollection;
use App\Http\Resources\V1\TrackingProduct\TrackingProductResource;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Repository\TrackingProductRepositoryInterface;
use App\Http\Services\Mutual\FileManagerService;
use Carbon\Carbon;

abstract class TrackingProductService extends PlatformService
{
    use Responser;

    public function __construct(
        private readonly TrackingProductRepositoryInterface $repository,
        private readonly FileManagerService $fileManagerService
    ) {}

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


        if ($request->hasFile('image')) {
            $data['image'] = $this->fileManagerService->handle('image', 'tracking_products');
        }
        if (isset($data['start_date'])) {
            $startDateRaw = $data['start_date'];
            $startDate = $this->normalizeDate($startDateRaw);

            if (!$startDate) {
                return $this->responseFail(message: 'Invalid start date format');
            }
            $data['start_date'] = $startDate->format('Y-m-d');
        }

        $endDateRaw = $data['end_date'];
        $endDate = $this->normalizeDate($endDateRaw);

        if (!$endDate) {
            return $this->responseFail(message: 'Invalid end date format');
        }

        $data['end_date'] = $endDate->format('Y-m-d');
        $now = Carbon::now();

        if ($endDate->isPast()) {
            $data['status'] = 'expired';
        } elseif ($endDate->diffInDays($now) <= 14) {
            $data['status'] = 'near-to-expire';
        } else {
            $data['status'] = 'in-date';
        }


        $trackingProduct = $this->repository->create($data);

        return $this->responseSuccess(data: new TrackingProductResource($trackingProduct));
    }

    public function update($request, $id)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $existingProduct = $this->repository->getById($id);
            $data['image'] = $this->fileManagerService->handle('image', 'tracking_products', $existingProduct->image);
        }

        if (isset($data['start_date'])) {
            $startDateRaw = $data['start_date'];
            $startDate = $this->normalizeDate($startDateRaw);

            if (!$startDate) {
                return $this->responseFail(message: 'Invalid start date format');
            }
            $data['start_date'] = $startDate->format('Y-m-d');
        }

        if (isset($data['end_date'])) {
            $endDateRaw = $data['end_date'];
            $endDate = $this->normalizeDate($endDateRaw);

            if (!$endDate) {
                return $this->responseFail(message: 'Invalid end date format');
            }

            $data['end_date'] = $endDate->format('Y-m-d');
            $now = Carbon::now();

            if ($endDate->isPast()) {
                $data['status'] = 'expired';
            } elseif ($endDate->diffInDays($now) <= 14) {
                $data['status'] = 'near-to-expire';
            } else {
                $data['status'] = 'in-date';
            }
        }

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

    protected function normalizeDate(string $dateRaw): ?Carbon
    {
        $formats = [
            'Y-m-d',    // Standard format
            'Y/m/d',    // Standard format
            'd-m-Y',    // Day-Month-Year
            'd/m/Y',    // Day-Month-Year
            'm-d-Y',    // Month-Day-Year
            'M-y',      // Month-Year (e.g., May-23)
            'm/y',      // Month/Year (e.g., 05/23)
            'd m Y' , // Day Month Year (e.g., 01 May 2023)
            'M/d/y', // Month/Day/Year (e.g., May/01/23)
        ];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $dateRaw);
                if ($date) {
                    return $date;
                }
            } catch (\Exception $e) {
                // Continue to the next format
            }
        }

        return null;
    }
}
