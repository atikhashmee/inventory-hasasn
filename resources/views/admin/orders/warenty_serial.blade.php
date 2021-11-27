@extends('layouts.app')

@push('page_css')
    <style>
        .quantity_serial {
            padding: 10px;
            background: #d3d3d3;
        }
    </style>
@endpush
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Set Warenty Serial Number</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="clearfix"></div>
        <form action="{{route("admin.submit.warenty.serial")}}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{$order_id}}">
            <div class="card">
                <div class="card-header">
                    <a href="{{route("admin.orders.show", ['order' => $order_id])}}" class="btn btn-primary">Back</a>
                    <button type="submit" class="btn btn-success float-right">Save Changes</button>
                </div>
                <div class="card-body">
                    @if (count($products) > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    @foreach ($products as $order_detail)
                                        <th>{{$order_detail->product_name}} <div class="badge badge-success">{{$order_detail->product_quantity}}</div> </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ($products as $order_detail)
                                        @php
                                            $serials = [];
                                            if (count($order_detail->warenty) > 0) {
                                                foreach ($order_detail->warenty as $wrnty) {
                                                    $serials[$wrnty->quanitty_serial_number] = $wrnty->serial_number;
                                                }
                                            }
                                        @endphp
                                        <th>
                                            <table border="1">
                                                @for ($i = 1; $i <= $order_detail->product_quantity; $i++)
                                                    <tr>
                                                        <td class="quantity_serial">{{$i}}</td>
                                                        <td> 
                                                            <input type="number" name="serial_number[{{$order_detail->id}}][{{$i}}][]" value="{{isset($serials[$i]) ? $serials[$i] : null }}" class="form-control">
                                                        </td>
                                                    </tr>
                                                @endfor
                                            </table>
                                        </th>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </form>
    </div>

@endsection

