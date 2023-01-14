@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sales Returns</h1>
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
            <div class="card-header">
                <a href="{{route('admin.order.return.create')}}" class="btn btn-primary float-right"> Create new</a>
            </div>
            <div class="card-body">
                {{-- <form action="{{route('admin.orders.index')}}" method="GET">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div></div>
                        <div class="d-flex" style="flex-basis: 40%">
                            <select name="shop_id" id="shop_id" class="form-control mr-2">
                                <option value="">Select a shop</option>
                                @foreach ($shops as $shop)
                                    <option value="{{$shop->id}}">{{$shop->name}}</option>
                                @endforeach
                            </select>
                            <input type="search" name="search" class="form-control" placeholder="Order Number, Customer Name" >
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form> --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Order&nbsp;ID</th>
                            <th>Product&nbsp;Name</th>
                            <th>Returned&nbsp;Quantity</th>
                            <th>Returned&nbsp;Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($orders) > 0)
                            @foreach ($orders as $key => $order)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$order->order_number}}</td>
                                    <td>{{$order->product_name}}</td>
                                    <td>{{$order->returned_quantity}}</td>
                                    <td>{{$order->returned_amount}}</td>
                                    <td>{{$order->created_at}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" onclick="document.getElementById('detailsForm').submit()">Delete</button>
                                        <form action="{{route('admin.order.return.rollback')}}" id="detailsForm" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="detail_id" value="{{$order->id}}">
                                        </form>
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
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{$orders->withQueryString()->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

