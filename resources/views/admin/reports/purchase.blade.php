@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @component('admin.reports.nav')@endcomponent
                </div>
                @php
                    $selectedYear = date('Y');
                    if (Request::get('year')) {
                        $selectedYear = Request::get('year');
                    }
                @endphp
                <div class="col-sm-6">
                    <form action="{{route('admin.report.purchase')}}">
                        <div class="d-flex flex-row-reverse">
                            <button class="btn btn-default" type="submit"><i class="fa fa-filter">Filter</i></button>
                            <select name="product_id" class="form-control select2">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{$product->id}}" @if(Request::get('product_id') == $product->id) selected @endif>{{$product->name}}</option>
                                @endforeach
                            </select>
                            <select name="supplier_id" class="form-control select2">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{$supplier->id}}" @if(Request::get('supplier_id') == $supplier->id) selected @endif>{{$supplier->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive h-70">
                    <table class="table tbl-border table-hover table-2nd-no-sort" id="file_export">
                        <thead>
                        <tr>
                            <th>Date/Month</th>
                            <th>January</th>
                            <th>February</th>
                            <th>March</th>
                            <th>April</th>
                            <th>May</th>
                            <th>June</th>
                            <th>July</th>
                            <th>August</th>
                            <th>September</th>
                            <th>October</th>
                            <th>November</th>
                            <th>December</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for ($i = 1; $i < 32; $i++)
                            <tr>
                                <td>{{ $i }}</td>
                                @for ($j = 1; $j < 13; $j++)
                                    <td>
                                        @if (isset($data[$j][$i]))
                                            <a href="{{route('admin.report.purchase_detail')}}?date={{$selectedYear}}-{{$j}}-{{$i}}">{{ $data[$j][$i]}}</a>
                                        @else 0
                                        @endif
                                    </td>
                                    @php
                                        $monthData[$j][] = isset($data[$j][$i]) ? $data[$j][$i] : 0;
                                    @endphp
                                @endfor
                            </tr>
                        @endfor
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Total</th>
                            @foreach ($monthData as $mon)
                                <th>{{ array_sum($mon) }}</th>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection



