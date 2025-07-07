<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\Product\ProductRequest;
use App\Http\Services\Dashboard\Product\ProductService;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $product)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->product->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->product->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        return $this->product->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return $this->product->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->product->edit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        return $this->product->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->product->destroy($id);
    }
}
