@extends('layouts.user')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('admin.orders.index')}}" method="GET">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex">
                            <div class="form-group  mr-2">
                                <input type="text" class="form-control" id="rangePicker">
                                <input type="hidden" name="start" id="start">
                                <input type="hidden" name="end" id="end">
                            </div>
                            <div class="form-group mr-2">
                                <select name="order_challan_type" id="order_challan_type" class="form-control">
                                    <option value="walk-in">Regular</option>
                                    <option value="challan">Condition</option>
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <select name="customer_id" id="customer_id" class="form-control select2">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->customer_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2">
                                <input type="search" name="search" class="form-control" placeholder="Order Number, Customer Name" >
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Order&nbsp;Number</th>
                            <th>Shop</th>
                            <th>Customer&nbsp;Name</th>
                            <th>Order&nbsp;Amount</th>
                            <th>Order&nbsp;Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($orders) > 0)
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $serial-- }}</td>
                                    <td>{{$order->order_number}}</td>
                                    <td>{{$order->shop->name ?? 'N/A'}}</td>
                                    <td>{{$order->customer->customer_name ?? 'N/A'}}</td>
                                    <td>{{$order->total_amount}}</td>
                                    <td>{{$order->created_at}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-flat"><i class="fas fa-cogs"></i></button>
                                            <button type="button" class="btn btn-default btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                              <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                <a class="dropdown-item" href="{{route('user.order_detail', ['order_id'=>$order])}}">Detail</a>
                                                <a class="dropdown-item" href="{{url('print-invoice/'.$order->id)}}" target="_blank">Print Invoice</a>
                                                <a class="dropdown-item" href="{{url('print-challan/'.$order->id)}}" target="_blank">Print Challan</a>
                                                @if (intval($order->wr_order_details) > 0)
                                                    <a class="dropdown-item" href="{{url('print-warenty-serials/'.$order->id)}}" target="_blank"></i>Print Warenty Serial</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
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
                {{$orders->links()}}
            </div>
        </div>
    </div>
@endsection