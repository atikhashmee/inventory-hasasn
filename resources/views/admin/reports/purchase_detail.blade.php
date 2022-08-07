@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="{{route('admin.report.purchase')}}" class="btn btn-default">Back</a>
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
            <div class="card-body p-0">
                @php
                    $totalAmount = 0; 
                    $totalQuantity = 0; 
                @endphp
                <div class="table-responsive h-70">
                    <table class="table text-center">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Product</th>
                            <th>Supplier</th>
                            <th>Warehouse</th>
                            <th>Price</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($stocks as $key =>  $stock)
                        @php
                            $totalAmount += $stock->price; 
                            $totalQuantity += $stock->quantity; 
                        @endphp
                            <tr>
                                <td>{{++$key}}</td>
                                <td class="text-left"> <span class="p-1" style="border: 1px solid #d3d3d3; font-size:14px">{{ ($stock->product)?$stock->product->code:'N' }}</span> {{ ($stock->product)?$stock->product->name:'N/A' }}</td>
                                <td>{{ ($stock->supplier)?$stock->supplier->name:'N/A' }}</td>
                                <td>{{ ($stock->warehouse)?$stock->warehouse->ware_house_name:'N/A' }}</td>
                                <td>{{ $stock->price }}</td>
                                <td>{{ $stock->quantity }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <div class="float-right">
                       Total Amount = {{$totalAmount}} <br>
                       Total Quantity = {{$totalQuantity}} 
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection



