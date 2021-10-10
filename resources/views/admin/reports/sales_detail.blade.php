@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="{{route('admin.report.sells')}}" class="btn btn-default">Back</a>
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
                <div class="table-responsive h-70">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order&nbsp;ID</th>
                                <th>Shop</th>
                                <th>Customer&nbsp;Name</th>
                                <th>Order&nbsp;Amount</th>
                                <th>Order&nbsp;Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($orders) > 0)
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{$order->order_number}}</td>
                                        <td>{{$order->shop->name ?? 'N/A'}}</td>
                                        <td>{{$order->customer->customer_name ?? 'N/A'}}</td>
                                        <td>{{$order->total_amount}}</td>
                                        <td>{{$order->created_at}}</td>
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="5">
                                    <strong>No Item founds</strong>
                                </td>
                            </tr>
                            @endif
                        </tbody>
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



