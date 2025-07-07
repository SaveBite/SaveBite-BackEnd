@extends('dashboard.core.app')
@section('title', __('dashboard.product_details'))
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('dashboard.product_details')</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('dashboard.product_details')</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr><th>@lang('dashboard.id')</th><td>{{ $product->id }}</td></tr>
                                <tr><th>@lang('dashboard.user_id')</th><td>@if($product->user)<a href="{{ route('users.show', $product->user->id) }}">{{ $product->user->user_name }}</a>@else-@endif</td></tr>
                                <tr><th>@lang('dashboard.date')</th><td>{{ $product->Date }}</td></tr>
                                <tr><th>@lang('dashboard.product_name')</th><td>{{ $product->ProductName }}</td></tr>
                                <tr><th>@lang('dashboard.category')</th><td>{{ $product->Category }}</td></tr>
                                <tr><th>@lang('dashboard.unit_price')</th><td>{{ $product->UnitPrice }}</td></tr>
                                <tr><th>@lang('dashboard.stock_quantity')</th><td>{{ $product->StockQuantity }}</td></tr>
                                <tr><th>@lang('dashboard.reorder_level')</th><td>{{ $product->ReorderLevel }}</td></tr>
                                <tr><th>@lang('dashboard.reorder_quantity')</th><td>{{ $product->ReorderQuantity }}</td></tr>
                                <tr><th>@lang('dashboard.units_sold')</th><td>{{ $product->UnitsSold }}</td></tr>
                                <tr><th>@lang('dashboard.sales_value')</th><td>{{ $product->SalesValue }}</td></tr>
                                <tr><th>@lang('dashboard.month')</th><td>{{ $product->Month }}</td></tr>
                                <tr><th>@lang('dashboard.created_at')</th><td>{{ $product->created_at }}</td></tr>
                                <tr><th>@lang('dashboard.updated_at')</th><td>{{ $product->updated_at }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('products.index') }}" class="btn btn-dark">@lang('dashboard.back_to_list')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
