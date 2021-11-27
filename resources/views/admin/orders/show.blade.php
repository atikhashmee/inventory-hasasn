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
                        <p><strong>Invoice&nbsp;No:</strong> {{$order->order_number}}</p>
                        <p><strong>Customer&nbsp;Name:</strong> {{$order->customer->customer_name}}</p>
                        <p><strong>Phone:</strong> {{$order->customer->customer_phone}}</p>
                        <p><strong>E-mail:</strong> {{$order->customer->customer_email}}</p>
                        <p><strong>Address:</strong> {{$order->customer->customer_address}}</p>
                    </div>   
                    <div class="time-info">
                        <p><strong>Date:</strong> {{date('Y-m-d', strtotime($order->created_at))}}</p>
                        <p><strong>Time:</strong> {{date('h:i a', strtotime($order->created_at))}}</p>
                        <p><strong>Sold&nbsp;by:</strong> {{$order->user->name}}</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>SL</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit&nbsp;Price</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            @php
                                $subtotal = 0;
                            @endphp
                            @if (count($order->orderDetail) > 0)
                                @foreach($order->orderDetail as $detail)
                                @php
                                     $subtotal += $detail->product_unit_price * $detail->product_quantity;
                                @endphp
                                    <tr>
                                        <td>{{$detail->id}}</td>
                                        <td>{{$detail->product_name}}</td>
                                        <td>{{$detail->quantity_unit_id!=null? $detail->quantity_unit_value.' '.$detail->unit->name: $detail->product_quantity }}</td>
                                        <td>{{$detail->product_unit_price}}</td>
                                        <td>{{$detail->product_unit_price * $detail->product_quantity}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right">
                                    Subtotal:
                                </td>
                                <td>{{$subtotal}}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">
                                    Discount (-):
                                </td>
                                <td>{{$order->discount_amount}}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">
                                    Grand Total:
                                </td>
                                <td>{{$subtotal - $order->discount_amount}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-left">
                        @if ($order->notes!=null || !is_null($order->notes))
                            <strong>Notes:</strong> {{$order->notes}}
                        @endif
                    </div>
                    <div class="float-right d-flex">
                        @if (count($wr_order_details) > 0)
                            <a href="{{url('admin/set-warenty-serial-number/'.$order->id)}}" type="button"  class="btn bg-gradient-success mr-1">
                                Warenty Serial
                            </a>
                            <a href="{{url('print-warenty-serials/'.$order->id)}}" type="button"  class="btn bg-gradient-success mr-1">
                                <i class="fas fa-print"></i>Print Warenty Serial
                            </a>
                        @endif
                        <a href="{{url('print-invoice/'.$order->id)}}" target="_blank" type="button"  class="btn bg-gradient-success mr-1">
                            <i class="fas fa-print"></i> Invoice/Bill
                        </a>
                        <a href="{{url('print-challan/'.$order->id)}}" target="_blank" class="btn bg-gradient-warning">
                            <i class="fas fa-print"></i> Challan
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

