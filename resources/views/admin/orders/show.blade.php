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
    
    <style>
        .due-total p {
            margin: 0;
            padding: 0;
        }
        @media print {
          body * {
            visibility: hidden;
          }
          .section-to-print, .section-to-print * {
            visibility: visible;
          }
          .section-to-print {
            position: absolute;
            width: 100%;
            left: 0;
            top: 20;
          }
          .print-avoid {
              visibility: hidden;
          }
          .time-info p, .order-info p {
            margin: 0;
            padding: 0;
            }
            .info-section {
                margin-bottom: 10px;
            }
        }
    </style>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-header">
                <div class="w-100 d-flex justify-content-between">
                    <a @if($order->prev_order_id!=null) href="{{route('admin.orders.show', ['order'=>$order->prev_order_id])}}" @else href="javascript:void(0)"  @endif class="btn btn-default @if($order->prev_order_id == null) disabled @endif">Prev</a>
                    <a @if($order->next_order_id!=null) href="{{route('admin.orders.show', ['order'=>$order->next_order_id])}}" @else href="javascript:void(0)"  @endif class="btn btn-default @if($order->next_order_id == null) disabled @endif">Next</a>
                </div>
            </div>
            <div class="card-body section-to-print">
                <div class="d-flex justify-content-between info-section">
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
                    <div class="float-left due-total">
                        <p>Current Due: {{number_format($current_due, 2, '.', ',')}}</p>
                        <p>Sales: {{number_format(($today_sales) , 2, '.', ',')}}</p>
                        <p>Collected: {{number_format(($total_collected) , 2, '.', ',')}}</p>
                        <p> <strong>Net Outstanding: {{ number_format($net_outstanding, 2, '.', ',') }}</strong></p>
                    </div>
                    <div class="float-left">
                        @if ($order->notes!=null || !is_null($order->notes))
                            <strong>Notes:</strong> {{$order->notes}}
                        @endif
                    </div>
                    <div class="float-right d-flex print-avoid">
                        @if ($order->status == "Drafted")
                            <a class="btn bg-gradient-success mr-1 print-avoid"  type="button" href="{{ route('admin.orders.create') }}?order_id={{$order->id}}">Edit & Sale</a>
                        @else
                            @if (count($wr_order_details) > 0)
                                <a href="{{url('admin/set-warenty-serial-number/'.$order->id)}}" type="button"  class="btn bg-gradient-success mr-1 print-avoid">
                                    Warranty Serial
                                </a>
                                <a href="{{url('print-warenty-serials/'.$order->id)}}" target="_blank" type="button"  class="btn bg-gradient-success mr-1 print-avoid">
                                    <i class="fas fa-print print-avoid"></i>Print Warranty Serial
                                </a>
                            @endif
                            <a href="{{url('print-invoice/'.$order->id)}}" target="_blank" type="button"  class="btn bg-gradient-success mr-1 print-avoid">
                                <i class="fas fa-print print-avoid"></i> Invoice/Bill
                            </a>
                            <a href="{{url('print-challan/'.$order->id)}}" target="_blank" class="btn bg-gradient-warning print-avoid">
                                <i class="fas fa-print print-avoid"></i> Challan
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

