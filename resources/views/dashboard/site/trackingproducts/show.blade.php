@extends('dashboard.core.app')
@section('title', __('dashboard.trackingproduct_details'))
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('dashboard.trackingproduct_details')</h1>
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
                            <h3 class="card-title">@lang('dashboard.trackingproduct_details')</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr><th>@lang('dashboard.id')</th><td>{{ $trackingProduct->id }}</td></tr>
                                <tr><th>@lang('dashboard.user_id')</th><td>@if($trackingProduct->user)<a href="{{ route('users.show', $trackingProduct->user->id) }}">{{ $trackingProduct->user->user_name }}</a>@else-@endif</td></tr>
                                <tr><th>@lang('dashboard.name')</th><td>{{ $trackingProduct->name }}</td></tr>
                                <tr><th>@lang('dashboard.numberId')</th><td>{{ $trackingProduct->numberId }}</td></tr>
                                <tr><th>@lang('dashboard.category')</th><td>{{ $trackingProduct->category }}</td></tr>
                                <tr><th>@lang('dashboard.quantity')</th><td>{{ $trackingProduct->quantity }}</td></tr>
                                <tr><th>@lang('dashboard.label')</th><td>{{ $trackingProduct->label }}</td></tr>
                                <tr><th>@lang('dashboard.start_date')</th><td>{{ $trackingProduct->start_date }}</td></tr>
                                <tr><th>@lang('dashboard.end_date')</th><td>{{ $trackingProduct->end_date }}</td></tr>
                                <tr><th>@lang('dashboard.status')</th><td>{{ $trackingProduct->status }}</td></tr>
                                <tr><th>@lang('dashboard.image')</th><td>@if($trackingProduct->image)<img src="{{ asset($trackingProduct->image) }}" alt="Image" style="max-width:120px;max-height:120px;object-fit:cover;">@else-@endif</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('trackingproducts.index') }}" class="btn btn-dark">@lang('dashboard.back_to_list')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
