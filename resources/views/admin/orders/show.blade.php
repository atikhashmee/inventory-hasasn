@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Detail</h1>
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
                <div class="d-flex justify-content-between">
                    <div class="order-info">
                        <p><strong>Invoice No:</strong> {{$order->order_number}}</p>
                        <p><strong>Customer Name:</strong> {{$order->customer->customer_name}}</p>
                        <p><strong>Phone:</strong> {{$order->customer->customer_phone}}</p>
                        <p><strong>E-mail:</strong> {{$order->customer->customer_email}}</p>
                        <p><strong>Address:</strong> {{$order->customer->customer_address}}</p>
                    </div>   
                    <div class="time-info">
                        <p><strong>Date Time:</strong> {{$order->create_at}}</p>
                        <p><strong>Sold by:</strong> {{$order->user_id}}</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>SL</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            @if (count($order->orderDetail) > 0)
                                @foreach($order->orderDetail as $detail)
                                    <tr>
                                        <td>{{$detail->id}}</td>
                                        <td>{{$detail->product_name}}</td>
                                        <td>{{$detail->product_quantity}}</td>
                                        <td>{{$detail->product_unit_price}}</td>
                                        <td>{{$detail->product_unit_price * $detail->product_quantity}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right d-flex">
                        <a href="{{url('print-invoice/'.$order->id)}}" target="_blank" type="button"  class="btn bg-gradient-success mr-1">
                            <i class="fas fa-print"></i> Invoice/Bill
                        </a>
                        <button type="button" class="btn bg-gradient-warning">
                            <i class="fas fa-print"></i> Challan
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

