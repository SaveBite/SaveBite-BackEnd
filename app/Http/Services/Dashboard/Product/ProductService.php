<?php

namespace App\Http\Services\Dashboard\Product;

use App\Http\Services\Mutual\FileManagerService;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(private readonly ProductRepositoryInterface $Repository,
                                private readonly FileManagerService $fileManagerService)
    {}

    public function index()
    {
        $products = $this->Repository->paginate(25);
        return view('dashboard.site.products.index', compact('products'));
    }

    public function create()
    {
        return view('dashboard.site.products.create');
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $product = $this->Repository->create($data);
            DB::commit();
            return redirect()->route('products.index', $product->id)->with(['success' => __('messages.created_successfully')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function show($id)
    {
        $product = $this->Repository->getById($id);
        return view('dashboard.site.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = $this->Repository->getById($id);
        return view('dashboard.site.products.edit', compact('product'));
    }

    public function update($request, $id)
    {
        try {
            $product = $this->Repository->getById($id);
            $data = $request->validated();
            $this->Repository->update($id, $data);
            return redirect()->route('products.update', $product->id)->with(['success' => __('messages.updated_successfully')]);
        } catch (\Exception $e) {
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->Repository->delete($id);
            return redirect()->back()->with(['success' => __('messages.deleted_successfully')]);
        } catch (\Exception $e) {
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }
}
