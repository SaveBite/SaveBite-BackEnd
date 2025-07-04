<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\ProductStoreRequest;
use App\Http\Requests\Api\V1\UploadCSVRequest;
use App\Http\Services\Api\V1\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->productService->index();
    }


    public function stock()
    {
        return $this->productService->stock();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        return $this->productService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function upload(UploadCSVRequest $request)
    {
        return $this->productService->upload($request);
    }

    public function analytics()
    {
        return $this->productService->analytics();
    }

    public function salesPredictions()
    {
        return $this->productService->salesPredictions();
    }
}
