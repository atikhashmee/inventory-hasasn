@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Drafted Sales</h1>
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
                                <select name="order_status" id="order_status" class="form-control">
                                    <option value="All">All</option>
                                    <option value="Drafted">Drafted</option>
                                    <option value="Sold">Sold</option>
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
                                <select name="supplier_id" id="supplier_id" class="form-control select2">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($user->role == 'admin')
                                <div class="form-group mr-2">
                                    <select name="shop_id" id="shop_id" class="form-control select2">
                                        <option value="">Select a shop</option>
                                        @foreach ($shops as $shop)
                                            <option value="{{$shop->id}}">{{$shop->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group mr-2">
                                <input type="search" name="search" class="form-control" placeholder="Order Number, Customer Name" >
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="dropdown" style="display: none">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="javascript:void()" onclick="submitDelete()">Delete All</a>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="allcheck" value="all">
                            </th>
                            <th>SL</th>
                            <th>Order&nbsp;Number</th>
                            <th>Shop</th>
                            <th>Customer&nbsp;Name</th>
                            <th>Order&nbsp;Amount</th>
                            <th>Order&nbsp;Date</th>
                            <th>Payment&nbsp;Status</th>
                            <th>Order&nbsp;Status</th>
                            <th>Sold&nbsp;By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($orders) > 0)
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="order_id" value="{{$order->id}}">
                                    </td>
                                    <td>{{ $serial-- }}</td>
                                    <td>{{$order->order_number}}</td>
                                    <td>{{$order->shop->name ?? 'N/A'}}</td>
                                    <td>{{$order->customer->customer_name ?? 'N/A'}}</td>
                                    <td>{{$order->total_amount}}</td>
                                    <td>{{$order->created_at}}</td>
                                    <td>
                                        @if ($order->order_total_payemnt >= $order->total_final_amount)
                                            Paid
                                        @else    
                                            @if ($order->order_total_payemnt == 0)
                                               Not Paid 
                                            @else
                                                Partial
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->status == "Drafted")
                                            Drafted
                                        @else    
                                            Sold
                                        @endif
                                    </td>
                                    <td>{{$order->user? $order->user->name : 'N/A'}}</td>
                                 
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

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('page_scripts')
    <script>
        let allOrders = [];
        let deletdUrl = `{{route("admin.order.draft.delete")}}`;
        $(".order_id").on("change", checkSingleOrder);
        $(".allcheck").on("change", allCheck);

        function checkSingleOrder(evt) {
            let obj = evt.currentTarget;
            let order_id = obj.value;
            if (obj.checked) {
                allOrders[order_id] = order_id
            } else {
                allOrders.splice(order_id, 1);
            }
        }

        function allCheck(evt) {
            let obj = evt.currentTarget;
            if (obj.checked) {
                $(".order_id").each(function(index, item) {
                    let elemId = $(this).val();
                    allOrders[elemId] = elemId
                })
            } else {
                allOrders = []
            }
            checkuncheck()
            makeModify()
        }
        function checkuncheck() {
            $(".order_id").each(function(index, item) {
                let elemId = $(this).val();
                if (allOrders[elemId] !== undefined) {
                    $(this).attr('checked', true)
                } else {
                    $(this).attr('checked', false)
                }
            })
        }

        function makeModify() {
            if (allOrders.length > 0) {
                $(".dropdown").css("display", "block")
            } else {
                $(".dropdown").css("display", "none")
            }
        }
        function submitDelete() {
            if (allOrders.length == 0) {
                alert("No Item found, selected")
            }
            let orderData = allOrders.filter(item => item)
            fetch(deletdUrl, {
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": `{{csrf_token()}}`,
                    'Content-Type': 'application/json'
                }, 
                body: JSON.stringify({
                    order_ids: orderData
                })
            }).then(res=>res.json())
            .then(res=>{
                if (res.status) {
                    window.location.reload()
                } else {
                    alert(res.message)
                }
            })
        }
    </script>
@endpush
