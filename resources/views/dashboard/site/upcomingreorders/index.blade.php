@extends('dashboard.core.app')
@section('title', __('dashboard.upcomingreorders'))
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('dashboard.upcomingreorders')</h1>
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
                            <h3 class="card-title">@lang('dashboard.upcomingreorders')</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('dashboard.user_id')</th>
                                    <th>@lang('dashboard.product_name')</th>
                                    <th>@lang('dashboard.category')</th>
                                    <th>@lang('dashboard.date')</th>
                                    <th>@lang('dashboard.reorder_quantity')</th>
                                    <th>@lang('dashboard.Operations')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($upcomingReorders as $upcomingReorder)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($upcomingReorder->user)
                                                <a href="{{ route('users.show', $upcomingReorder->user->id) }}">{{ $upcomingReorder->user->user_name }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $upcomingReorder->ProductName }}</td>
                                        <td>{{ $upcomingReorder->Category }}</td>
                                        <td>{{ $upcomingReorder->Date }}</td>
                                        <td>{{ $upcomingReorder->ReorderQuantity }}</td>
                                        <td>
                                            <div class="operations-btns">
                                                <a href="{{ route('upcomingreorders.show', $upcomingReorder->id) }}" class="btn btn-dark">@lang('dashboard.Show')</a>
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
                                                                <form action="{{ route('upcomingreorders.destroy', $upcomingReorder->id) }}" method="post">
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
                                    @include('dashboard.core.includes.no-entries', ['columns' => 7])
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
