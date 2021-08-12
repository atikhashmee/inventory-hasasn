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
                       href="javascript:void(0)" @click="saveUpdates()">
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
                                    <input type="checkbox" v-model="selectAll" value="true">
                                </th>
                                <th>Product</th>
                                <th>Warehouse Quantity</th>
                                <th>@{{selectedShop!==null?selectedShop.name: '-'}} current quantity</th>
                                <th>@{{selectedShop!==null?selectedShop.name: '-'}} Add quantity</th>
                                <th>@{{selectedShop!==null?selectedShop.name: '-'}} total quantity</th>
                                <th>@{{selectedShop!==null?selectedShop.name: '-'}} Price</th>
                            </tr>
                        </thead>
                        <tbody v-if="products.length > 0">
                            <tr v-for="(product, index) in products" :key="index">
                                <td> <input type="checkbox" v-model="product_ids" name="item_id" :value="product.id"></td>
                                <td>@{{product.name}}</td>
                                <td>@{{product.final_warehouse_quantity ?? product.warehouse_quantity}}</td>
                                <td>@{{product.shop_quantity??0}}</td>
                                <td>
                                    <input type="number" 
                                    class="form-control" 
                                    @keyup="updateQuantity($event, product)"
                                    :max="product.warehouse_quantity">
                                </td>
                                <td>@{{product.total_quantity??0}}</td>
                                <td>
                                    <input type="number" class="form-control" @keyup="updatePrice($event, product)">
                                </td>
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
                product_ids: [],
                selectAll: null,
            },
            mounted(){
                this.getResource()
            },
            watch: {
                selectAll(oldval, newval) {
                    if (oldval) {
                        this.product_ids = this.products.map(item=>item.id)
                    } else {
                        this.product_ids = [] 
                    }
                }
            },
            methods: {
                getResource() {
                    this.products = []
                    this.product_ids = []
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
                            if (this.products.length > 0) {
                                this.products.forEach(itemD => {
                                   if (('isAdded' in itemD) && itemD.isAdded !== null) {
                                       this.product_ids.push(itemD.id)
                                   }
                                });
                            }
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
                                item.new_quantity = Number(inputQuantity)
                                item.total_quantity = Number(item.shop_quantity) + Number(inputQuantity)
                            }
                            return item;
                        });
                    }
                },
                updatePrice(evt, product) {
                    inputPrice = evt.currentTarget.value;
                    if (this.products.length > 0) {
                        this.products = this.products.map(item => {
                            if (product.id === item.id) {
                                item.new_price = Number(inputPrice)
                            }
                            return item;
                        });
                    }
                },
                saveUpdates() {
                    if (this.product_ids.length === 0) {
                        alert('You have no item selected')
                    } else {
                        let url = `{{route('admin.shop_products.store')}}`
                        let formD = new FormData()
                        formD.append('shop_id', this.selectedShop.id)
                        formD.append('products', JSON.stringify(this.products))
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                "X-CSRF-TOKEN": `{{csrf_token()}}`
                            }, 
                            body: formD
                        })
                        .then(res=>res.json())
                        .then(res=>{
                            if (res.status) {
                                window.location.reload()
                            }
                        })
                    }
                }
            }
        })
    </script>
@endpush

