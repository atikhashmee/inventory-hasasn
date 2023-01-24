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
            <div class="card-body" id="draftsOrder">
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
                <div class="dropdown" v-if="order_ids.length > 0">
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
                                <input type="checkbox" class="allcheck" v-model="selectAll" value="all">
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($orders) > 0)
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="order_id" v-model="order_ids" value="{{$order->id}}">
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
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-flat"><i class="fas fa-cogs"></i></button>
                                            <button type="button" class="btn btn-default btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                              <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" style="">
                                                @if ($order->status == "Drafted")
                                                    <a class="dropdown-item" href="{{ route('admin.orders.create') }}?order_id={{$order->id}}">Edit & Sale</a>
                                                @endif
                                                <a class="dropdown-item" href="{{route('admin.orders.show', ['order'=>$order])}}">Detail</a>
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

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('third_party_scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
@endpush

@push('page_scripts')
    <script>
        let allOrders = [];
        let deletdUrl = `{{route("admin.order.draft.delete")}}`;
    
        let draftOrdersApp = new Vue({
            el: '#draftsOrder', 
            data: {
                order_ids: [],
                orders: {!! json_encode(count($orders->getCollection()) > 0 ? $orders->getCollection()->toArray() : [], JSON_HEX_TAG) !!},
                selectAll: null
            },
            mounted(){
            },
            computed: {
            
            },
            watch: {
                selectAll(oldval, newval) {
                    if (oldval) {
                        this.order_ids = this.orders.map(item=>item.id)
                    } else {
                        this.order_ids = [] 
                    }
                }
            },
            methods: {
               
            }
        })
        function submitDelete() {
            if (draftOrdersApp.order_ids.length == 0) {
                alert("No Item found, selected")
            }
            fetch(deletdUrl, {
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": `{{csrf_token()}}`,
                    'Content-Type': 'application/json'
                }, 
                body: JSON.stringify({
                    order_ids: draftOrdersApp.order_ids
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
