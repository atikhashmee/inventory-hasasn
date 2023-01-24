@extends('layouts.app')
@push('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Customers Outstandings list</h1>
                    <p>(Customers list with outstanding past 7 days)</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Customers Outstandings list</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                {{-- <div class="card-header">
                    <form action="{{ route("admin.outstandingCustomers") }}" method="GET">
                        <div class="d-flex flex-wrap">
                            <div class="form-group input-form-group mr-2">
                                <input type="text" class="form-control mr-2" id="rangePicker">
                                <input type="hidden" name="start" id="start">
                                <input type="hidden" name="end" id="end">
                            </div>
                        
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"> <i class="fa fa-filter">Filter</i> </button>
                            </div>
                        </div>
                    </form>
                </div> --}}
                <div class="card-body p-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($outstanding_dues) > 0)
                                @foreach ($outstanding_dues as $key => $customer)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $customer->id }}</td>
                                        <td>{{ $customer->customer_name }}</td>
                                        <td>{{ number_format($customer->total_due, 2, ".", ",") }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{ $outstanding_dues->links() }}
                </div>
            </div>
        </div>
    </section>
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