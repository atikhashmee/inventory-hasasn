@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Purchase</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('admin.stocks.create') }}">
                        Add New
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <form action="{{ route('admin.stocks.index') }}" method="get">
                            <div class="d-flex flex-row-reverse">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"> <i class="fa fa-filter">Filter</i> </button>
                                </div>
                                <div class="form-group w-20 mr-2">
                                    <select name="product_id" class="form-control select2">
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{$product->id}}" @if(Request::get('product_id') == $product->id) selected @endif>{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group w-20 mr-2">
                                    <select name="supplier_id" class="form-control select2">
                                        <option value="">Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{$supplier->id}}" @if(Request::get('supplier_id') == $supplier->id) selected @endif>{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  mr-2 w-20">
                                    <input type="text" class="form-control" id="rangePicker">
                                    <input type="hidden" name="start" id="start">
                                    <input type="hidden" name="end" id="end">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('admin.stocks.table')

                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $stocks->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
@push('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@push('third_party_scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endpush

@push('page_scripts')
    <script>
        $('#rangePicker').daterangepicker();
        $('#rangePicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            $('#start').val(picker.startDate.format('YYYY-MM-DD'))
            $('#end').val(picker.endDate.format('YYYY-MM-DD'))
        });
    </script>
@endpush