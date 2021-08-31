@extends('layouts.app')

@push('page_css')
    <style>
        
    </style>
@endpush

@section('content')
<div id="shop_products">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product Distribution</h1>
                </div>
                <div class="col-sm-6">
                    
                </div>
            </div>
        </div>
    </section>

    
      <div class="content px-3">
        @include('flash::message')

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Wareshouse to Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Shop to Shop</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="activity">
                        <form action="">
                            <div class="row">
                                <div class="col-md-6 d-flex justify-content-between align-items-center">
                                    <div class="form-group" style="flex-basis: 40%">
                                        <label for="">Warehouse</label>
                                        <select name="warehouse_id" id="warehouse_id" v-model="warehosue_id" class="form-control">
                                            <option value="">Select a Warehouse</option>
                                            <option v-for="warehosue in warehouses" :value="warehosue.id">@{{warehosue.ware_house_name}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="flex-basis: 40%">
                                        <label for="">Shop</label>
                                        <select name="shop_id" id="shop_id" v-model="shop_id" class="form-control">
                                            <option value="">Select a shop</option>
                                            <option v-for="shop in shops" :value="shop.id">@{{shop.name}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="flex-basis: 8%">
                                        <label for=""></label>
                                        <button type="button" @click="getShopData()" class="btn btn-primary">Query</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <a class="btn btn-primary float-right" href="javascript:void(0)" @click="saveUpdates()">
                                        Update
                                    </a>
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" v-model="selectAll" value="true">
                                        </th>
                                        <th>Product</th>
                                        <th>Warehouse Quantity</th>
                                        <th>@{{selectedShop!==null?selectedShop.name: '-'}} Current&nbsp;Quantity</th>
                                        <th>@{{selectedShop!==null?selectedShop.name: '-'}} Add&nbsp;Quantity</th>
                                        <th>@{{selectedShop!==null?selectedShop.name: '-'}} Total&nbsp;Quantity</th>
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
                                            <input type="number" class="form-control" @keyup="updateQuantity($event, product)" :max="product.warehouse_quantity">
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
                    <div class="tab-pane" id="timeline">
                        <div class="col-md-6">
                            <form id="shoptoshopfrom">
                                <div class="form-group">
                                    <label for="shop_from">Shop From</label>
                                    <select class="form-control" name="shop_from" id="shop_from" v-model="shopToShop.shop_from" required>
                                        <option value="">Select a shop</option>
                                        <option v-for="shop in shop_from" :value="shop.id">@{{shop.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="shop_to">Shop To</label>
                                    <select class="form-control" name="shop_to" id="shop_to" v-model="shopToShop.shop_to" required>
                                        <option value="">Select a shop</option>
                                        <option v-for="shop in shop_to" :value="shop.id">@{{shop.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="product_id">Product</label>
                                    <select class="form-control" name="product_id" id="product_id" v-model="shopToShop.product_id" required>
                                        <option value="">Select a Product</option>
                                        <option v-for="product in shop_from_products" :value="product.id">@{{product.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="selectedProductInfo!==null">
                                    <label for="">Available Quantity</label>
                                    <input type="text" readonly class="form-control" :value="selectedProductInfo.available_quanity" />
                                    <input type="hidden" name="price" :value="selectedProductInfo.price">
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input class="form-control" type="number" id="quantity" name="quantity"  :max="selectedProductInfo!==null?selectedProductInfo.available_quanity:0" v-model="shopToShop.quantity" required />
                                </div>
                                <button class="btn btn-primary" type="button" @click="updateShopToShop">Transfer</button>
                            </form>
                        </div>
                    </div>
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
        let shopProductapp = new Vue({
            el: '#shop_products', 
            data: {
                products: [],
                shops: [],
                warehouses: [],
                shop_id: '',
                warehosue_id: '',
                selectedShop: null,
                product_ids: [],
                selectAll: null,
                shop_from_products: [],
                shop_from: [],
                shop_to: [],
                shopToShop: {
                    shop_from: "",
                    shop_to: "",
                    product_id: '',
                    quantity: 0,
                },
                selectedProductInfo: null,
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
                },
                "shopToShop.shop_from": {
                    handler(oldval, newval) {
                        if (oldval.length !== undefined && oldval.length === 0) {
                            this.shop_to = [...this.shops]
                            this.getShopFromProducts(oldval)
                        } else  {
                            this.getShopFromProducts(oldval)
                            this.shop_to = this.shops.filter(item=>item.id!==oldval)
                        }
                    },
                    deep: true,
                },
                "shopToShop.product_id":{
                    handler(oldval, newval) {
                        if (oldval) {
                            this.getProductQuantity(oldval)
                        }
                    }, 
                    deep: true
                }
            },
            methods: {
                getResource() {
                    this.products = []
                    this.product_ids = []
                    let url = `{{url('admin/shop_products/get-resource')}}`

                    if (this.warehosue_id !== '' && this.selectedShop !== null) {
                        url =  `{{url('admin/shop_products/get-resource')}}?shop_id=${this.selectedShop.id}&warehouse_id=${this.warehosue_id}`
                    }

                    fetch(url)
                    .then(res=>res.json())
                    .then(res=>{
                        if (res.status) {
                            this.shops = [...res.data.shops]
                            this.shop_from = [...res.data.shops]
                            this.shop_to = [...res.data.shops]
                            this.products = [...res.data.products]
                            this.warehouses = [...res.data.warehosues]
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
                getShopData() {
                    if (this.warehosue_id==='') {
                        alert('Select a warehouse')
                        return
                    }

                    if (this.shop_id==='') {
                        alert('Select a shop')
                        return
                    }
                    
                    let shop_id = this.shop_id
                    let warehosue_id = this.warehosue_id
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
                        formD.append('warehouse_id', this.warehosue_id)
                        formD.append('shop_id', this.selectedShop.id)
                        formD.append('products', JSON.stringify(this.products.filter(item=>this.product_ids.includes(item.id))))
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
                },
                getProductQuantity(product_id) {
                    if (this.shop_from_products.length > 0) {
                        let selectedProduct = this.shop_from_products.find(it=>it.id===product_id)
                        if (selectedProduct) {
                            this.selectedProductInfo = selectedProduct
                        }
                    }
                },
                updateShopToShop() {
                    let url = `{{route('admin.shop_products.updateshoptoshop')}}`
                    let formD = new FormData(document.getElementById('shoptoshopfrom'))
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            "X-CSRF-TOKEN": `{{csrf_token()}}`
                        }, 
                        body: formD
                    })
                    .then(res=>res.json())
                    .then(res=>{
                        console.log(res, 'asdf');
                        if (res.status) {
                        }
                    })
                },
                getShopFromProducts(shop_from) {
                    if (shop_from) {
                        let url =  `{{url('admin/get_shop_products')}}/${shop_from}`
                        fetch(url)
                        .then(res=>res.json())
                        .then(res=>{
                            if (res.status) {
                                this.shop_from_products = [...res.data.products]
                            }
                        })
                    }
                }
            }
        })
    </script>
@endpush

