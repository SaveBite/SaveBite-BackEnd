<?php

namespace App\Http\Controllers\Api\V1\TrackingProduct;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TrackingProduct\TrackingProductStoreRequest;
use App\Http\Requests\Api\V1\TrackingProduct\TrackingProductUpdateRequest;
use App\Http\Services\Api\V1\TrackingProduct\TrackingProductService;
use Illuminate\Http\Request;

class TrackingProductController extends Controller
{
    public function __construct(private readonly TrackingProductService $trackingProductService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->trackingProductService->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrackingProductStoreRequest $request)
    {
        return $this->trackingProductService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->trackingProductService->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TrackingProductUpdateRequest $request, string $id)
    {
        return $this->trackingProductService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->trackingProductService->destroy($id);
    }
}
