@extends('layouts.user')
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
                <form action="{{route('user.transactions.index')}}" method="GET">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div></div>
                        <div class="d-flex justify-content-around align-items-center">
                            <input type="text" class="form-control mr-2" id="rangePicker">
                            <input type="hidden" name="start" id="start">
                            <input type="hidden" name="end" id="end">
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
                @include('user.transactions.table')

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
                <table border="1" class="ml-auto p-2">
                    <tr>
                        <td colspan="5">Total Deposit</td>
                        <td class="text-right">{{$totalDiposit}}</td>
                    </tr>
                    <tr>
                        <td colspan="5">Total Withdraw</td>
                        <td class="text-right">{{$totalWithdraw}}</td>
                    </tr>
                    <tr>
                        <td colspan="5">Due</td>
                        <td class="text-right">125485</td>
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
