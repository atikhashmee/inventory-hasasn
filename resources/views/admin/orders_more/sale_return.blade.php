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
                            <span v-if="errors?.order_id?.length > 0" role="alert"  class="text-danger">@{{ errors.order_id[0] }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Select Product</label>
                            <select name="detail_id" id="detail_id" v-model="detailObj.detail_id" class="form-control custom_detail_product select2">
                                <option value="">Select</option>
                                <option v-for="det in orderDetails" :value="det.id">@{{det.product_name}}</option>
                            </select>
                            <span v-if="errors?.detail_id?.length > 0" role="alert"  class="text-danger">@{{ errors.detail_id[0] }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Quantity</label>
                            <input type="number" class="form-control" v-model="detailObj.quantity" :max="detailObj.available_quantity" name="quantity" id="quantity">
                            <small>Returnable Quantity @{{detailObj.available_quantity}}</small> <br>
                            <small>Amount Returnable @{{detailObj.unit_price * detailObj.available_quantity}}</small>
                            <span v-if="errors?.quantity?.length > 0" role="alert"  class="text-danger">@{{ errors.quantity[0] }}</span>
                        </div>
                        <div class="form-group">
                            <label for="">Cash returned ?</label>
                            <input type="checkbox" v-model="detailObj.cash_returned" name="cash_returned" id="cash_returned">
                        </div>
                        <div class="form-group" v-if="detailObj.cash_returned">
                            <label for="">Price</label>
                            <input type="number" class="form-control" v-model="detailObj.returnedPrice" name="price" id="price">
                            <small>Amount Returnable @{{detailObj.unit_price * detailObj.available_quantity}}</small> <br>
                            <span v-if="errors?.returnedPrice?.length > 0" role="alert"  class="text-danger">@{{ errors.returnedPrice[0] }}</span>
                        </div>
                        <div class="form-group">
                            <span v-if="error" role="alert"  class="text-danger">@{{ error }}</span>
                            <span v-if="msg" role="alert"  class="text-success">@{{ msg }}</span>
                        </div>
                        <button class="btn btn-success" type="button" @click="submitReturnedOrder()">Submit Return</button>
                        <a class="btn btn-default" href="{{ route('admin.order.return') }}">Back</a>
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
                    order_id: '',
                    detail_id: '',
                    available_quantity: 0,
                    quantity: '',
                    unit_price: 0,
                    returnedPrice: '',
                    cash_returned: false,
                },
                errors: {},
                error: null,
                msg: '',
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
                        if (res.status) {
                             this.msg = res.msg;
                             window.location.reload();
                        } else {
                            if (res.errors) {
                                this.errors = res.errors
                            }

                            if (res.error) {
                                this.error = res.error
                            }
                        }
                    })
                
                }
            }
        })
    </script>
@endpush