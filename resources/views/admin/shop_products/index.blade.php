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
                        Add New
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
                        <label for="">Select a Shop</label>
                        <select name="shop_id" id="shop_id" class="form-control">
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
                                <th>Shop-name current quantity</th>
                                <th>Shop-name total quantity</th>
                            </tr>
                        </thead>
                        <tbody v-if="products.length > 0">
                            <tr v-for="(product, index) in products" :key="index">
                                <td> <input type="checkbox" name="item_id"></td>
                                <td>@{{product.name}}</td>
                                <td>@{{product.name}}</td>
                                <td>@{{product.name}}</td>
                                <td>@{{product.name}}</td>
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
            },
            mounted(){
                this.getResource()
            },
            methods: {
                getResource() {
                    fetch(`{{url('admin/shop_products/get-resource')}}`)
                    .then(res=>res.json())
                    .then(res=>{
                        if (res.status) {
                            this.shops = [...res.data.shops]
                            this.products = [...res.data.products]
                        }
                    })
                }
            }
        })
    </script>
@endpush

