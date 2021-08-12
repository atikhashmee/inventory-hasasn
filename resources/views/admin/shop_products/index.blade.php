@extends('layouts.app')

@section('content')
<div id="shop_products">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shop Products</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('admin.products.create') }}">
                        Update
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3" >

        @include('flash::message')

        <div class="card">
            <div class="card-header">
                Shop products
            </div>
            <div class="card-body">
                <form action="">
                    <div class="form-group">
                        <label for="">Shop</label>
                        <select name="shop_id" id="shop_id" class="form-control" @change="selectShop($event)">
                            <option value="">Select a shop</option>
                            <option v-for="shop in shops" :value="shop.id">@{{shop.name}}</option>
                        </select>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox">
                                </th>
                                <th>Product</th>
                                <th>Warehouse Quantity</th>
                                <th>@{{selectedShop!==null?selectedShop.name: '-'}} current quantity</th>
                                <th>@{{selectedShop!==null?selectedShop.name: '-'}} Add quantity</th>
                                <th>@{{selectedShop!==null?selectedShop.name: '-'}} total quantity</th>
                            </tr>
                        </thead>
                        <tbody v-if="products.length > 0">
                            <tr v-for="(product, index) in products" :key="index">
                                <td> <input type="checkbox" name="item_id"></td>
                                <td>@{{product.name}}</td>
                                <td>@{{product.warehouse_quantity}}</td>
                                <td>@{{product.shop_quantity??'-'}}</td>
                                <td>
                                    <input type="number" 
                                    class="form-control" 
                                    @keyup="updateQuantity($event, product)"
                                    :max="product.warehouse_quantity">
                                </td>
                                <td>@{{product.total_quantity??0}}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
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
        let shopProductapp = new Vue({
            el: '#shop_products', 
            data: {
                products: [],
                shops: [],
                selectedShop: null,
            },
            mounted(){
                this.getResource()
            },
            methods: {
                getResource() {
                    let url = `{{url('admin/shop_products/get-resource')}}`
                    if (this.selectedShop !== null) {
                        url =  `{{url('admin/shop_products/get-resource')}}?shop_id=${this.selectedShop.id}`
                    }
                    fetch(url)
                    .then(res=>res.json())
                    .then(res=>{
                        if (res.status) {
                            this.shops = [...res.data.shops]
                            this.products = [...res.data.products]
                        }
                    })
                }, 
                selectShop(evt) {
                    let shop_id = evt.currentTarget.value
                    let selectedshop =this.shops.find(it=>it.id==shop_id)
                    if (selectedshop) {
                        this.selectedShop = selectedshop
                        this.getResource()
                    }
                },
                updateQuantity(evt, product) {
                    let inputQuantity = evt.currentTarget.value;
                    if(isNaN(inputQuantity)){
                        evt.preventDefault();
                        evt.currentTarget.value = 1;
                    }
                    if (Number(inputQuantity) > Number(product.warehouse_quantity)) {
                        evt.preventDefault();
                        evt.currentTarget.value = product.warehouse_quantity;
                    }
                    inputQuantity = evt.currentTarget.value;
                    if (this.products.length > 0) {
                        this.products = this.products.map(item => {
                            if (product.id === item.id) {
                                item.total_quantity = Number(item.shop_quantity) + Number(inputQuantity)
                            }
                            return item;
                        });
                    }
                }
            }
        })
    </script>
@endpush

