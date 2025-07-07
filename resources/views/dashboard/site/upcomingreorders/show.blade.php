@extends('dashboard.core.app')
@section('title', __('dashboard.upcomingreorder_details'))
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('dashboard.upcomingreorder_details')</h1>
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
                            <h3 class="card-title">@lang('dashboard.upcomingreorder_details')</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr><th>@lang('dashboard.id')</th><td>{{ $upcomingReorder->id }}</td></tr>
                                <tr><th>@lang('dashboard.user_id')</th><td>@if($upcomingReorder->user)<a href="{{ route('users.show', $upcomingReorder->user->id) }}">{{ $upcomingReorder->user->user_name }}</a>@else-@endif</td></tr>
                                <tr><th>@lang('dashboard.product_name')</th><td>{{ $upcomingReorder->ProductName }}</td></tr>
                                <tr><th>@lang('dashboard.category')</th><td>{{ $upcomingReorder->Category }}</td></tr>
                                <tr><th>@lang('dashboard.date')</th><td>{{ $upcomingReorder->Date }}</td></tr>
                                <tr><th>@lang('dashboard.reorder_quantity')</th><td>{{ $upcomingReorder->ReorderQuantity }}</td></tr>
                                <tr><th>@lang('dashboard.created_at')</th><td>{{ $upcomingReorder->created_at }}</td></tr>
                                <tr><th>@lang('dashboard.updated_at')</th><td>{{ $upcomingReorder->updated_at }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('upcomingreorders.index') }}" class="btn btn-dark">@lang('dashboard.back_to_list')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
