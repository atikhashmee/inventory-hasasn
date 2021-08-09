@extends('layouts.user')

@section('content')
    <div class="container mt-5" id="order_new">
        <div class="row">
            <div class="col-md-12">
                <form action="#" method="POST">
                    <div class="d-flex justify-content-between align-item-center">
                        <div class="shop-info-section">
                            @{{name}}
                        </div>
                        <div class="order-info-section">
                            <div class="form-group d-flex">
                                <label for="">Order ID</label>
                                <input type="text" name="order_id" class="form-control" /> 
                            </div>
                            <div class="form-group d-flex">
                                <label for="">Order Date</label>
                                <input type="date" name="date" class="form-control" /> 
                            </div>
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
                                        @{{pl.item_id}}
                                    </td>
                                    <td>
                                        @{{pl.product_id}}
                                        @{{pl.product_name}}
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
@endsection

@section('page_js')
    <script>
        let order_app = new Vue({
            el: '#order_new',
            data: {
                name: 'jjj',
                product_lists: [],
            },
            methods: {
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
@endsection