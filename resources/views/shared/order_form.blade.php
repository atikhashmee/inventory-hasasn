<div class="card">
    <div class="card-body">
        <input type="hidden" id="user_role" value="{{$role}}">
        <div class="container mt-5" id="order_new">
            <div class="row">
                <div class="col-md-12">
                    <form action="#" method="POST" @submit.prevent="submitOrder()" autocomplete="off">
                        <div class="d-flex justify-content-between align-item-center">
                            <div class="shop-info-section">
                                
                                @if (auth()->user()->role == 'admin')
                                    <img src="{{asset('assets/img/not-found.png')}}" id="shop_image" width="200" height="200" alt="">
                                @else
                                    @php
                                        if (file_exists(public_path().'/uploads/shops/'.auth()->user()->shop->image)  && auth()->user()->shop->image) {
                                            $shop_logo = asset('/uploads/shops/'.auth()->user()->shop->image);
                                        } else {
                                            $shop_logo = asset('assets/img/not-found.png');
                                        }
                                    @endphp
                                        <img src="{{$shop_logo}}" id="shop_image" width="200" height="200" alt="">
                                @endif
                            </div>
                            <div class="order-info-section">
                                <table class="table table-bordered">
                                    <tr>
                                        <td><label for="">Sale&nbsp;ID</label></td>
                                        <td>
                                            <div class="d-flex">
                                                <input type="text" name="order_id" readonly class="form-control" v-model="order_id" /> 
                                                <button type="button" class="btn btn-primary btn-sm" @click="order_id=salesIdDateFormat()">Change</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="">Sale&nbsp;Date</label></td>
                                        <td>
                                            <div class="d-flex">
                                                <input type="date" v-model="order_date" class="form-control" name="date">
                                            </div>
                                        </td>
                                    </tr>
                                    @if ($role == 'admin')
                                        <tr>
                                            <td><label for="">Shop</label></td>
                                            <td>
                                                <select name="shop_id" id="shop_id" @change="shopChangeGetData($event)" v-model="shop_id" class="form-control"> 
                                                    <option value="">Select a shop</option>
                                                    @foreach ($shops as $shop)
                                                        <option value="{{$shop->id}}" data-img="{{$shop->image_link}}">{{$shop->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @else
                                        <input type="hidden" name="shop_id" id="shop_id" value="{{$user->shop_id}}">
                                    @endif
                                    <tr>
                                        <td><label for="">Sales Type</label></td>
                                        <td>
                                            <select name="sales_type" id="sales_type" class="form-control" v-model="sale_type">
                                                <option value="Walk-in">Regular</option>
                                                <option value="challan">Condition</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        {{-- customer info --}}
                        <div class="card">
                            <div class="card-header">
                                Customer Info
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Name <span class="text-danger">*</span> </label>
                                            <input type="text" 
                                            autocomplete="off" 
                                            name="customer_name" 
                                            id="customer_name" 
                                            v-model="customer.customer_name" 
                                            class="form-control" 
                                            placeholder="Enter Customer Name" /> 
                                            <small>(Type at list two character two get the lists, I:e, ae, ab. )</small>
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
                                            <label for="">Customer Phone<span class="text-danger">*</span></label>
                                            <input type="text" 
                                            name="customer_phone" 
                                            v-model="customer.customer_phone" 
                                            class="form-control" 
                                            placeholder="Enter Customer Phone" /> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Customer type</label>
                                        <select name="customer_type" id="customer_type" class="form-control"  v-model="customer.customer_type" >
                                            <option value="">Select a type</option>
                                            @foreach ($customer_types as $c_type)
                                                <option>{{$c_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">District</label>
                                            <input type="text" 
                                            name="district" 
                                            v-model="customer.district" 
                                            class="form-control" 
                                            placeholder="district" /> 
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
                            </div>
                        </div>
                        
                       {{-- order item --}}
                        <div class="card">
                            <div class="card-header">Sell Items</div>
                            <div class="card-body">
                                <div class="table-area">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th style="width: 40%">Description</th>
                                                <th style="width: 30%">Quantity</th>
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
                                                    <small v-if="shop_id===''">(Select a shop to get the product lists)</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <select name="quantity_unit_id" v-model="pl.quantity_unit_id" id="quantity_unit_id" style="flex-basis: 80%" @change="fieldUpdate($event, pl, 'quantity_unit')" class="form-control">
                                                            <option value="">Unit X 1</option>
                                                            <option :value="unit.id" v-for="unit in allUnits">@{{unit.name}} X @{{unit.quantity_base}}</option>
                                                        </select>
                                                        <div>
                                                            <input type="number" step="any" class="form-control" v-model="pl.input_quantity" :max="pl.available_quantity" @blur="fieldUpdate($event, pl, 'quantity')" name="quantity">
                                                            <small>Available Quantity @{{pl.available_quantity}}</small>
                                                            <small>Quantity @{{pl.quantity}}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" v-model="pl.price" @blur="fieldUpdate($event, pl, 'price')" name="price">
                                                </td>
                                                <td>@{{pl.totalPrice}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <button class="btn btn-primary" type="button" @click="addToCart()">Add Product</button>
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
                                                <td colspan="5">
                                                    <input type="number" class="form-control" name="discount_amount" id="discount_amount" v-model.number="discount">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right">Total</td>
                                                <td colspan="5">@{{subTotalValue - discount}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label for="">Addtional note (Optional) </label>
                                    <textarea name="note" id="note" v-model="note" class="form-control"></textarea>
                                    <small>Visible to Invoice page as well </small>
                                </div>
                                <div class="form-group">
                                    <label for="">Challan note (Optional) </label>
                                    <textarea name="challan_note" id="challan_note" v-model="challan_note" class="form-control"></textarea>
                                    <small>Visible to challan </small>
                                </div>
                            </div>
                        </div>
                       {{-- payment section --}}
                        <div class="card">
                            <div class="card-header">
                                Payment Section
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Total Payable</label>
                                    <input type="text" readonly class="form-control" :value="subTotalValue - discount">
                                </div>
                                <div class="form-group">
                                    <label for="">Payment Type @{{payment_type}}</label>
                                    <select name="payment_type" id="payment_type" v-model="payment_type" class="form-control">
                                        <option value="">Select a Payment Type</option>
                                        @foreach (App\Models\Transaction::$paymentType as $paymentType)
                                            <option>{{$paymentType}}</option>   
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Pay Now</label>
                                    <input type="number" name="payment_amount" v-model.number="payment_amount" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right" :disabled="loader" type="submit">Create New Sale</button>
                        <span v-if="error" class="text-danger">@{{error}}</span>
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

