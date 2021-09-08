@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
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
                <div class="container mt-5" id="order_new">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="#" method="POST" @submit.prevent="submitOrder()" autocomplete="off">
                                <div class="d-flex justify-content-between align-item-center">
                                    <div class="shop-info-section">
                                    </div>
                                    <div class="order-info-section">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td><label for="">Order&nbsp;ID</label></td>
                                                <td>
                                                    <div class="d-flex">
                                                        <input type="text" name="order_id" readonly class="form-control" v-model="order_id" /> 
                                                        <button type="button" class="btn btn-primary btn-sm" @click="order_id=Date.now()">Change</button>
                                                    </div>
                                                   
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="">Order&nbsp;Date</label></td>
                                                <td>
                                                    <div class="d-flex">
                                                        <input type="date" v-model="order_date" class="form-control" name="date">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="">Shop</label></td>
                                                <td>
                                                    <select name="shop_id" id="shop_id" @change="shopChangeGetData($event)" v-model="shop_id" class="form-control"> 
                                                        <option value="">Select a shop</option>
                                                        @foreach ($shops as $shop)
                                                            <option value="{{$shop->id}}">{{$shop->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Name <span class="text-danger">*</span> </label>
                                            <input t
                                            ype="text" 
                                            autocomplete="off" 
                                            name="customer_name" 
                                            id="customer_name" 
                                            v-model="customer.customer_name" 
                                            class="form-control" 
                                            placeholder="Enter Customer Name" /> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Email</label>
                                            <input type="text" 
                                            name="customer_email" 
                                            v-model="customer.customer_email" 
                                            class="form-control" 
                                            placeholder="Enter Customer Email" /> 
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Phone</label>
                                            <input type="text" 
                                            name="customer_phone" 
                                            v-model="customer.customer_phone" 
                                            class="form-control" 
                                            placeholder="Enter Customer Phone" /> 
                                        </div>
                                    </div>
                                </div>
                               <div class="row">
                                   <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Customer Address</label>
                                            <textarea name="customer_address" v-model="customer.customer_address" id="customer_address" class="form-control" cols="30"></textarea>
                                        </div>
                                   </div>
                               </div>
                                
                                <div class="table-area">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th style="width: 50%">Description</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="pl in product_lists" class="rowclass" :data-id="pl.item_id">
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-danger rounded-circle" @click="delete_item(pl)">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <select name="product_id" class="form-control product_id_custom_select select2">
                                                        <option value=""></option>
                                                        <option v-for="prod in products" :value="prod.id" :item-id="prod.item_id">@{{prod.name}}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" v-model="pl.quantity" :max="pl.quantity" @keyup="fieldUpdate($event, pl, 'quantity')" name="quantity">
                                                    <small>Available Quantity @{{pl.available_quantity}}</small>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" v-model="pl.price" @keyup="fieldUpdate($event, pl, 'price')" name="price">
                                                </td>
                                                <td>@{{pl.totalPrice}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <button class="btn btn-primary" type="button" @click="addToCart()">Add Row</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right">
                                                    Sub Total
                                                </td>
                                                <td colspan="5">@{{subTotalValue}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right">Discount</td>
                                                <td colspan="5">@{{discount}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right">Total</td>
                                                <td colspan="5">@{{subTotalValue - discount}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <button class="btn btn-primary float-right" type="submit">Place Order</button>
                            </form>
                        </div>
                    </div>
                </div>

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
    <script>
        var orderListUrl =  "{{ route('admin.orders.index') }}"
          Vue.directive('select2', {
            inserted(el) {
                $(el).on('select2:select', () => {
                    const event = new Event('change', {bubbles: true, cancelable: true});
                    el.dispatchEvent(event);
                });
                $(el).on('select2:unselect', () => {
                    const event = new Event('change', {bubbles: true, cancelable: true})
                    el.dispatchEvent(event)
                })
            },
        });
        function initLibs() {
            setTimeout(function () {
                $('.select2').select2({
                    width: '100%',
                    placeholder: 'Select',
                });
                $('.product_id_custom_select').on('select2:select', function (e) {
                    let item_id = $(e.currentTarget).closest('.rowclass').data('id');
                    let product_id = e.currentTarget.value
                    let product_item = order_app.products.find(it => it.id == product_id)
                    order_app.product_lists = order_app.product_lists.map(item=>{
                        if (item.item_id === item_id) {
                            item.product_id = product_item.id
                            item.product_name = product_item.name
                            item.quantity = product_item.shop_quantity
                            item.available_quantity = product_item.shop_quantity
                            item.price = product_item.price
                        }
                        return item;
                    })

                });
            }, 10);
        }
        
    </script>
@endpush
@push('page_scripts')
<script>
    let order_app = new Vue({
        el: '#order_new',
        data: {
            product_lists: [],
            products: [],
            product_items: [],
            subtotal: 0,
            total: 0, 
            discount: 0,
            order_id: null,
            shop_id: "",
            order_date: null,
            customer: {
                customer_name: null,
                customer_email: null,
                customer_phone: null,
                customer_address: null,
            }
        },
        mounted() {
            this.order_id = Date.now()
        },
        computed: {
            subTotalValue() {
                this.subtotal =  this.product_lists.reduce((total, item)=> total += Number(item.quantity) * Number(item.price), 0)
                return  this.subtotal;
            }
        },
        methods: {
            getResource() {
                this.products = []
                this.product_ids = []
                let url = `{{route('getCustomers')}}`
                fetch(url)
                .then(res=>res.json())
                .then(res=>{
                    if (res.status) {
                        this.customers = [...res.data.customers]
                    }
                })
            }, 
            addToCart() {
                let newitem = {}
                newitem.item_id = Date.now() + 1
                newitem.product_id = null,
                newitem.product_name = null,
                newitem.quantity = 0
                newitem.available_quantity = 0
                newitem.price = 0
                newitem.totalPrice = 0
                this.product_lists.push(newitem)
                initLibs()
            },
            delete_item(obj) {
                this.product_lists.splice(this.product_lists.indexOf(obj), 1)
            },
            fieldUpdate(evt, obj, type) {
                this.product_lists = this.product_lists.map(item=> {
                    if (obj.item_id === item.item_id) {
                        if (type === 'quantity') {
                            item.quantity = evt.currentTarget.value
                        } else if (type === 'price') {
                            item.price = evt.currentTarget.value
                        }
                    }
                    item.totalPrice =  item.price * item.quantity
                    return item;
                })
            },
            updateListProducts() {
                this.products.forEach(product => {
                    this.product_lists.forEach(cartitem=>{
                        if (product.id === cartitem.product_id) {
                            this.product_items = this.product_items.filter(item=>item.id !== product.id)
                        }
                    })
                });
            },
            submitOrder() {
                let orderObj = {};
                orderObj.items = {...this.product_lists}
                orderObj.order_number = this.order_id;
                orderObj.date = this.order_date;
                orderObj.customer_name = this.customer.customer_name;
                orderObj.customer_address = this.customer.customer_address;
                orderObj.customer_phone = this.customer.customer_phone;
                orderObj.customer_email = this.customer.customer_email;
                orderObj.subtotal = this.subtotal;
                orderObj.discount = this.discount;
                orderObj.shop_id = this.shop_id;
                fetch(`{{route('admin.orders.store')}}`, {
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": `{{csrf_token()}}`,
                        'Content-Type': 'application/json'
                    }, 
                    body: JSON.stringify(orderObj)
                })
                .then(res=>res.json())
                .then(res=>{
                    console.log(res, 'asdf');
                    if (res.status) {
                        window.location.href= `{{url('admin/orders/')}}/${res.data.id}`;
                    }
                })
            },
            shopChangeGetData(evt) {
                let shop_id = evt.currentTarget.value
                this.products = []
                this.product_ids = []
                let url = `{{url('shop_stock_products')}}/${shop_id}`
                fetch(url)
                .then(res=>res.json())
                .then(res=>{
                    if (res.status) {
                        this.products = [...res.data.products]
                        this.product_items = [...res.data.products]
                    }
                })
            }
        }
    })
    
    $(function(){
        $( "#customer_name" ).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: `{{route('getCustomers')}}`,
                    data: {
                        term: request.term
                    },
                    success: function( res ) {
                        if (res.status) {
                          response(res.data.customers);
                        }
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                order_app.customer = {...ui.item}
            }
        })
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( `<div>${item.customer_name} <br/> <small>${item.customer_address}</small> </div>` )
        .appendTo( ul );
    };
  });
</script>
@endpush
