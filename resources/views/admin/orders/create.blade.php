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
                            <form action="#" method="POST">
                                <div class="d-flex justify-content-between align-item-center">
                                    <div class="shop-info-section">
                                        
                                    </div>
                                    <div class="order-info-section">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td><label for="">Order&nbsp;ID</label></td>
                                                <td><input type="text" name="order_id" class="form-control" /> </td>
                                            </tr>
                                            <tr>
                                                <td><label for="">Order&nbsp;Date</label></td>
                                                <td>
                                                    <input type="date" name="date" class="form-control" /> 
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Customer Name</label>
                                    <input type="text" name="order_id" class="form-control" /> 
                                </div>
                                <div class="form-group">
                                    <label for="">Customer Address</label>
                                    <input type="text" name="order_id" class="form-control" /> 
                                </div>
                                <div class="table-area">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="pl in product_lists">
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-danger rounded-circle" @click="delete_item(pl)">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <select name="product_id" class="form-control select2" v-select2>
                                                        <option v-for="prod in products" :value="prod.id">@{{prod.name}}</option>
                                                    </select>
                                                </td>
                                                <td>@{{pl.quantity}}</td>
                                                <td>@{{pl.price}}</td>
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
                                                <td colspan="5">12000</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right">Discount</td>
                                                <td colspan="5">12000</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right">Total</td>
                                                <td colspan="5">12000</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <button class="btn btn-primary float-right" type="button">Place Order</button>
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
            }, 10);
        }
    </script>
@endpush
@push('page_scripts')
<script>
    let order_app = new Vue({
        el: '#order_new',
        data: {
            name: 'jjj',
            product_lists: [],
            products: []
        },
        mounted() {
            this.getResource()
            initLibs()
        },
        methods: {
            getResource() {
                this.products = []
                this.product_ids = []
                let url = `{{route('admin.order.getResource')}}`
                fetch(url)
                .then(res=>res.json())
                .then(res=>{
                    if (res.status) {
                        this.products = [...res.data.products]
                    }
                })
            }, 
            addToCart() {
                let newitem = {}
                newitem.item_id = 20
                newitem.product_id = 20
                newitem.product_name = 20
                newitem.quantity = 20
                newitem.price = 20
                newitem.totalPrice = 20
                this.product_lists.push(newitem)
            },
            delete_item(obj) {
                this.product_lists.splice(this.product_lists.indexOf(obj), 1)
            }
        }
    })
</script>
@endpush
