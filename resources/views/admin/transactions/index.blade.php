@extends('layouts.app')
@push('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Transactions</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('admin.transactions.create') }}">
                        Create New Transaction
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.transactions.index')}}" method="GET">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span>{{count($transactions)}} Records showing</span>
                        </div>
                        <div class="d-flex justify-content-around align-items-center">
                            <input type="text" class="form-control mr-2" id="rangePicker">
                            <input type="hidden" name="start" id="start">
                            <input type="hidden" name="end" id="end">
                            <select name="payment_type" id="payment_type" class="form-control select2">
                                <option value="">Select a Payment Type</option>
                                @foreach (App\Models\Transaction::$paymentType as $paymentType)
                                    <option>{{$paymentType}}</option>   
                                @endforeach
                            </select>
                            <select name="customer_id" id="customer_id" class="form-control select2">
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->customer_name}}</option>
                                @endforeach
                            </select>
                            <div style="flex-basis: 35%">
                                <button type="submit" class="btn btn-primary ml-2 d-flex align-items-center">Filter <i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                @include('admin.transactions.table')

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
                <table border="1" class="table">
                    <tr>
                        <td colspan="5">Total Payment</td>
                        <td class="text-right">{{'+'.$totalDiposit}}</td>
                    </tr>
                    <tr>
                        <td colspan="5">Total Sales</td>
                        <td class="text-right">{{'-'.$totalWithdraw}}</td>
                    </tr>
                    <tr>
                        <td colspan="5">Due / Balance / Remaining (+) admin has to pay to customer (-) customer has to pay to admin</td>
                        <td class="text-right">{{$totalDiposit - $totalWithdraw}}</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>

@endsection

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

