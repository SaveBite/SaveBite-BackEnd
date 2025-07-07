<?php

namespace App\Http\Services\Dashboard\TrackingProduct;

use App\Repository\TrackingProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TrackingProductService
{
    public function __construct(private readonly TrackingProductRepositoryInterface $repository)
    {
    }

    public function index()
    {
        $trackingProducts = $this->repository->paginate(25, ['user']);
        return view('dashboard.site.trackingproducts.index', compact('trackingProducts'));
    }

    public function show($id)
    {
        $trackingProduct = $this->repository->getById($id, ['*'], ['user']);
        return view('dashboard.site.trackingproducts.show', compact('trackingProduct'));
    }

    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            return redirect()->back()->with(['success' => __('messages.deleted_successfully')]);
        } catch (\Exception $e) {
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }
}
