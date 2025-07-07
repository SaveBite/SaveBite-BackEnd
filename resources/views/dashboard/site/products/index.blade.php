@extends('dashboard.core.app')
@php
    use \Illuminate\Support\Facades\Gate;
@endphp
@section('title', __('dashboard.products'))
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('dashboard.products')</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('dashboard.products')</h3>
                            <div class="card-tools">
                                <!-- Removed Create button -->
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('dashboard.user_id')</th>
                                    <th>@lang('dashboard.date')</th>
                                    <th>@lang('dashboard.product_name')</th>
                                    <th>@lang('dashboard.category')</th>
                                    <th>@lang('dashboard.unit_price')</th>
                                    <th>@lang('dashboard.stock_quantity')</th>
                                    <th>@lang('dashboard.reorder_level')</th>
                                    <th>@lang('dashboard.reorder_quantity')</th>
                                    <th>@lang('dashboard.units_sold')</th>
                                    <th>@lang('dashboard.sales_value')</th>
                                    <th>@lang('dashboard.month')</th>
                                    <th>@lang('dashboard.Operations')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($product->user)
                                                <a href="{{ route('users.show', $product->user->id) }}">{{ $product->user->user_name }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $product->Date }}</td>
                                        <td>{{ $product->ProductName }}</td>
                                        <td>{{ $product->Category }}</td>
                                        <td>{{ $product->UnitPrice }}</td>
                                        <td>{{ $product->StockQuantity }}</td>
                                        <td>{{ $product->ReorderLevel }}</td>
                                        <td>{{ $product->ReorderQuantity }}</td>
                                        <td>{{ $product->UnitsSold }}</td>
                                        <td>{{ $product->SalesValue }}</td>
                                        <td>{{ $product->Month }}</td>
                                        <td>
                                            <div class="operations-btns">
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-dark">@lang('dashboard.Show')</a>
                                                <button class="btn btn-danger waves-effect waves-light" data-toggle="modal" data-target="#delete-modal{{ $loop->iteration }}">@lang('dashboard.delete')</button>
                                                <div id="delete-modal{{ $loop->iteration }}" class="modal fade modal2 " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content float-left">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">@lang('dashboard.confirm_delete')</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>@lang('dashboard.sure_delete')</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" data-dismiss="modal" class="btn btn-dark waves-effect waves-light m-l-5 mr-1 ml-1">@lang('dashboard.close')</button>
                                                                <form action="{{ route('products.destroy', $product->id) }}" method="post">
                                                                    @csrf
                                                                    {{ method_field('delete') }}
                                                                    <button type="submit" class="btn btn-danger">@lang('dashboard.Delete')</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    @include('dashboard.core.includes.no-entries', ['columns' => 13])
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js_addons')
@endsection
