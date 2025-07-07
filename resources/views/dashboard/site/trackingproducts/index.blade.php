@extends('dashboard.core.app')
@section('title', __('dashboard.trackingproducts'))
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('dashboard.trackingproducts')</h1>
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
                            <h3 class="card-title">@lang('dashboard.trackingproducts')</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>@lang('dashboard.id')</th>
                                    <th>@lang('dashboard.user_id')</th>
                                    <th>@lang('dashboard.name')</th>
                                    <th>@lang('dashboard.numberId')</th>
                                    <th>@lang('dashboard.category')</th>
                                    <th>@lang('dashboard.quantity')</th>
                                    <th>@lang('dashboard.label')</th>
                                    <th>@lang('dashboard.start_date')</th>
                                    <th>@lang('dashboard.end_date')</th>
                                    <th>@lang('dashboard.status')</th>
                                    <th>@lang('dashboard.image')</th>
                                    <th>@lang('dashboard.Operations')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($trackingProducts as $trackingProduct)
                                    <tr>
                                        <td>{{ $trackingProduct->id }}</td>
                                        <td>
                                            @if($trackingProduct->user)
                                                <a href="{{ route('users.show', $trackingProduct->user->id) }}">{{ $trackingProduct->user->user_name }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $trackingProduct->name }}</td>
                                        <td>{{ $trackingProduct->numberId }}</td>
                                        <td>{{ $trackingProduct->category }}</td>
                                        <td>{{ $trackingProduct->quantity }}</td>
                                        <td>{{ $trackingProduct->label }}</td>
                                        <td>{{ $trackingProduct->start_date }}</td>
                                        <td>{{ $trackingProduct->end_date }}</td>
                                        <td>{{ $trackingProduct->status }}</td>
                                        <td>
                                            @if($trackingProduct->image)
                                                <img src="{{ asset($trackingProduct->image) }}" alt="Image" style="width:40px;height:40px;object-fit:cover;">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="operations-btns">
                                                <a href="{{ route('trackingproducts.show', $trackingProduct->id) }}" class="btn btn-dark">@lang('dashboard.Show')</a>
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
                                                                <form action="{{ route('trackingproducts.destroy', $trackingProduct->id) }}" method="post">
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
                                    @include('dashboard.core.includes.no-entries', ['columns' => 14])
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
