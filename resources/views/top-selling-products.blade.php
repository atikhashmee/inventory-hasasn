@extends('layouts.app')
@push('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Top Selling Products</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Top Selling Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route("admin.topSellingProducts") }}" method="GET">
                        <div class="d-flex flex-wrap">
                            <div class="form-group input-form-group mr-2">
                                <input type="text" class="form-control mr-2" id="rangePicker">
                                <input type="hidden" name="start" id="start">
                                <input type="hidden" name="end" id="end">
                            </div>
                            <div class="form-group input-form-group mr-2">
                                <select name="brand_id" class="form-control select2" style="width:100%;">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" @if(Request::get("brand_id") == $brand->id) selected @endif>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                                {{-- <div class="form-group input-form-group">
                                    <input type="text"  class="form-control" name="search" style="width:100%;" placeholder="Search....">
                                </div> --}}
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"> <i class="fa fa-filter">Filter</i> </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Product</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($best_selling_products) > 0)
                                @foreach ($best_selling_products as $key => $prod)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $prod->name }}</td>
                                        <td>{{ $prod->totalCOunt }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table> 
                    {{ $best_selling_products->links() }}
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