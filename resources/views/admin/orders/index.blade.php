@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sales</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.orders.index')}}" method="GET">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex">
                            <div class="form-group  mr-2">
                                <input type="text" class="form-control" id="rangePicker">
                                <input type="hidden" name="start" id="start">
                                <input type="hidden" name="end" id="end">
                            </div>
                            <div class="form-group mr-2">
                                <select name="order_challan_type" id="order_challan_type" class="form-control">
                                    <option value="walk-in">Regular</option>
                                    <option value="challan">Condition</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <select name="customer_id" id="customer_id" class="form-control select2">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->customer_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <select name="shop_id" id="shop_id" class="form-control select2">
                                    <option value="">Select a shop</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{$shop->id}}">{{$shop->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <input type="search" name="search" class="form-control" placeholder="Order Number, Customer Name" >
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                @include('admin.orders.table')

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
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
