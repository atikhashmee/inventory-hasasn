@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create Sale Return</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3" id="sell_return">

        @include('adminlte-templates::common.errors')
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Select Order</label>
                            <select name="order_id" id="order_id" v-model="detailObj.order_id" class="form-control custom_order_detail select2">
                                <option value="">Select</option>
                                <option v-for="ordr in orders" :value="ordr.id">(@{{ordr.shop.name}})  @{{ordr.order_number}} | @{{ordr.customer.customer_name}} | @{{ordr.total_final_amount}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Select Product</label>
                            <select name="detail_id" id="detail_id" v-model="detailObj.detail_id" class="form-control custom_detail_product select2">
                                <option value="">Select</option>
                                <option v-for="det in orderDetails" :value="det.id">@{{det.product_name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Quantity</label>
                            <input type="number" class="form-control" v-model="detailObj.quantity" :max="detailObj.available_quantity" name="quantity" id="quantity">
                            <small>Returnable Quantity @{{detailObj.available_quantity}}</small>
                        </div>
                        <div class="form-group">
                            <label for="">Price</label>
                            <input type="number" class="form-control" v-model="detailObj.returnedPrice" name="price" id="price">
                        </div>
                        <button class="btn btn-success" type="button" @click="submitReturnedOrder()">Submit Return</button>
                    </div>
        
                    <div class="card-footer">
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
        function initLibs(params) {
            $('.custom_order_detail').on('select2:select', function (e) {
                let order_id = e.currentTarget.value
                returnApp.detailObj.order_id = order_id
                let order = returnApp.orders.find(odd => odd.id == order_id)
                if (order) {
                    returnApp.orderDetails = order.order_detail.map(item=>{
                        let obj = {}
                        obj.id = item.id
                        obj.product_name = item.product_name
                        obj.quantity = item.final_quantity
                        obj.product_unit_price = item.product_unit_price
                        obj.price = item.product_unit_price * item.final_quantity
                        return obj;
                    })
                }
            });
            $('.custom_detail_product').on('select2:select', function (e) {
                let detail_id = e.currentTarget.value
                returnApp.detailObj.detail_id = detail_id
                let detail = returnApp.orderDetails.find(odd => odd.id == detail_id)
                if (detail) {
                    returnApp.detailObj.available_quantity = detail.quantity;
                    returnApp.detailObj.unit_price = detail.product_unit_price;
                }
            });
        }
        let returnApp = new Vue({
            el: '#sell_return',
            data: {
                orders: {!! json_encode(count($orders) > 0 ? $orders->toArray() : [], JSON_HEX_TAG) !!},
                orderDetails: [],
                detailObj: {
                    order_id: null,
                    detail_id: null,
                    available_quantity: 0,
                    quantity: 0,
                    unit_price: 0,
                    returnedPrice: 0,
                },
            },
            mounted() {
                initLibs()
            },
            methods: {
                submitReturnedOrder() {
                    fetch(`{{route('admin.order.return.update')}}`,  {
                        method: 'POST',
                        headers: {
                            "X-CSRF-TOKEN": `{{csrf_token()}}`,
                            'Content-Type': 'application/json'
                        }, 
                        body: JSON.stringify(this.detailObj)
                    }).then(res=>res.json())
                    .then(res=>{
                        console.log(res, 'asdfas');
                    })
                
                }
            }
        })
    </script>
@endpush