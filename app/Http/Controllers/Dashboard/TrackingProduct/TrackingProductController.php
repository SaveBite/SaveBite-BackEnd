<?php

namespace App\Http\Controllers\Dashboard\TrackingProduct;

use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\TrackingProduct\TrackingProductService;

class TrackingProductController extends Controller
{
    public function __construct(private readonly TrackingProductService $service)
    {
    }

    public function index()
    {
        return $this->service->index();
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
