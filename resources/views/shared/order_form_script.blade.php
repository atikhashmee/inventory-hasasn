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
                    minimumInputLength: 3,
                    maximumInputLength: 20,
                    //minimumResultsForSearch: 10
                });
                $('.product_id_custom_select').on('select2:select', function (e) {
                    let item_id = $(e.currentTarget).closest('.rowclass').data('id');
                    let product_id = e.currentTarget.value
                    let product_item = order_app.products.find(it => it.id == product_id)
                    /* check if the product is already added in the cart */
                    let ifExist = order_app.product_lists.find(dt=>dt.product_id == product_item.id)
                    if (ifExist) {
                        alert('Already added in the list')
                        order_app.product_lists = order_app.product_lists.filter(cartitem=>cartitem.product_id!==null)
                        return;
                    } else {
                        order_app.product_lists = order_app.product_lists.map(item=>{
                            if (item.item_id === item_id) {
                                item.product_id = product_item.id
                                item.warenty_duration = product_item.warenty_duration
                                item.product_name = product_item.name
                                item.product_purchase_price = product_item.product_cost
                                item.product_selling_price = product_item.selling_price
                                item.quantity = 0
                                item.input_quantity = 0
                                item.quantity_unit_id = ''
                                item.available_quantity = product_item.shop_quantity
                                item.price = product_item.selling_price
                                item.totalPrice = Number(item.quantity) * Number(item.price)
                            }
                            return item;
                        })
                    }
                });
            }, 10);
        }
        function initLibs2() {
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
            loader: false,
            isDrafted: false,
            product_lists: [],
            products: [],
            product_items: [],
            allUnits: {!! json_encode(count($units) > 0 ? $units->toArray() : [], JSON_HEX_TAG) !!},
            subtotal: 0,
            total: 0, 
            discount: 0,
            order_id: null,
            shop_id: "",
            sale_type: "Walk-in",
            note: "",
            challan_note: "",
            order_date: `{{date('Y-m-d')}}`,
            payment_amount: 0,
            payment_type: '',
            customer: {
                customer_name: null,
                customer_email: null,
                customer_phone: null,
                customer_address: null,
                customer_type: "",
                district: null,
            },
            user_role: null,
            error: '',
            draftedOrder: null,
        },
        mounted() {
            this.populateDraftedOrder();
            this.user_role = document.querySelector('#user_role').value;
            if (this.user_role !== 'admin' && !('user_id' in this.draftedOrder)) {
                this.shop_id = document.querySelector('#shop_id').value;
                this.shopDataFetch(this.shop_id)
            }
        },
        updated() {
            // console.log(this.product_lists, 'asf');
        },
        computed: {
            subTotalValue() {
                this.subtotal =  this.product_lists.reduce((total, item)=> total += Number(item.quantity) * Number(item.price), 0)
                return  this.subtotal;
            }
        },
        watch: {
            shop_id(oldval, newval) {
              //
            }
        },
        methods: {
            addToCart() {
                let newitem = {}
                newitem.item_id = Date.now() + 1
                newitem.product_id = null,
                newitem.product_name = null,
                newitem.input_quantity = 0,
                newitem.quantity = 0
                newitem.available_quantity = 0
                newitem.quantity_unit_id=''
                newitem.quantity_unit=null
                newitem.product_purchase_price=0
                newitem.product_selling_price=0
                newitem.price = 0
                newitem.totalPrice = 0
                newitem.warenty_duration = null
                this.product_lists.push(newitem)
                initLibs()
            },
            draftedAddCart(obj) {
                let product_item = this.products.find(it => it.id == obj.product_id)
                let newitem = {}
                newitem.item_id = obj.item_id
                newitem.product_id = obj.product_id,
                newitem.product_name = obj.product_name,
                newitem.input_quantity =  obj.product_quantity,
                newitem.quantity = obj.product_quantity,
                newitem.available_quantity =  product_item.shop_quantity
                newitem.quantity_unit_id=''
                newitem.quantity_unit=null
                newitem.product_purchase_price=product_item.product_cost
                newitem.product_selling_price=product_item.selling_price
                newitem.price = product_item.selling_price
                newitem.totalPrice = Number(newitem.quantity) * Number(newitem.price)
                newitem.warenty_duration = product_item.shop_quantity
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
                            let givenQuantity = evt.currentTarget.value;
                            if (item.quantity_unit ===null) {
                                if (Number(givenQuantity) > Number(item.available_quantity)) {
                                    item.input_quantity = Number(item.available_quantity)
                                    item.quantity = Number(item.available_quantity)
                                } else {
                                    item.input_quantity = givenQuantity
                                    item.quantity = givenQuantity
                                }
                            } else {
                                let unitData = {...item.quantity_unit};
                                if ((Number(givenQuantity) * Number(unitData.quantity_base)) > Number(item.available_quantity)) {
                                    item.quantity_unit = null;
                                    item.quantity_unit_id = '';
                                    item.input_quantity = Number(item.available_quantity)
                                    item.quantity = Number(item.available_quantity)
                                    alert('Maximum quantity limit exceeds')
                                } else {
                                    item.input_quantity = givenQuantity
                                    item.quantity = (Number(givenQuantity) * Number(unitData.quantity_base))
                                } 
                            }
                        } else if (type === 'price') {
                            let inputPrice = evt.currentTarget.value
                            if (Number(inputPrice) < Number(item.product_purchase_price)) {
                                item.price = item.product_purchase_price
                                alert('Price can not be less than Purchase price')
                            } else {
                                item.price = evt.currentTarget.value
                            }
                        } else if (type === 'quantity_unit') {
                            item.quantity_unit_id = evt.currentTarget.value;
                            item.quantity_unit = item.quantity_unit_id.length === 0?null:{...this.allUnits.find(ud=>ud.id==item.quantity_unit_id)};
                            let givenQuantity = item.input_quantity || 1;
                            if (item.quantity_unit !== null) {
                                let unitData = {...item.quantity_unit};
                                if ((Number(givenQuantity) * Number(unitData.quantity_base)) > Number(item.available_quantity)) {
                                    item.quantity_unit = null;
                                    item.quantity_unit_id = '';
                                    item.input_quantity = Number(item.available_quantity)
                                    item.quantity = Number(item.available_quantity)
                                    alert('Maximum quantity limit exceeds')
                                } else {
                                    item.input_quantity = givenQuantity
                                    item.quantity = (Number(givenQuantity) * Number(unitData.quantity_base))
                                }  
                            } else {
                                if (Number(givenQuantity) > Number(item.available_quantity)) {
                                    item.input_quantity = Number(item.available_quantity)
                                    item.quantity = Number(item.available_quantity)
                                } else {
                                    item.input_quantity = givenQuantity
                                    item.quantity = givenQuantity
                                }
                            }

                        }
                    }
                    item.totalPrice =  item.price * item.quantity
                    return item;
                })
            },
            draftOrder() {
                if (confirm("Are you sure?")) {
                    this.isDrafted  = true;
                    this.submitOrder();
                }
            },
            submitOrder() {
                this.loader = true;
                let orderObj = {};
                orderObj.items = {...this.product_lists}
                orderObj.order_number = this.order_id;
                orderObj.date = this.order_date;
                orderObj.sale_type = this.sale_type;
                orderObj.customer_name = this.customer.customer_name;
                orderObj.customer_address = this.customer.customer_address;
                orderObj.customer_phone = this.customer.customer_phone;
                orderObj.customer_email = this.customer.customer_email;
                orderObj.customer_type = this.customer.customer_type;
                orderObj.district = this.customer.district;
                orderObj.subtotal = this.subtotal;
                orderObj.discount = this.discount;
                orderObj.shop_id = this.shop_id;
                orderObj.note = this.note;
                orderObj.challan_note = this.challan_note;
                orderObj.payment_amount = this.payment_amount;
                orderObj.payment_type = this.payment_type;
                orderObj.order_status = this.isDrafted;
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
                    this.loader = false;
                    if (res.status) {
                        window.location.href= `{{url('admin/orders/')}}/${res.data.id}`;
                    } else {
                        this.error = res.data
                    }
                })
            },
            shopDataFetch(shop_id, callback = null) {
                this.products = []
                this.product_ids = []
                this.product_lists = []
                let url = `{{url('shop_stock_products')}}/${shop_id}`
                fetch(url)
                .then(res=>res.json())
                .then(res=>{
                    if (res.status) {
                        this.products = [...res.data.products]
                        this.product_items = [...res.data.products]
                        if (callback != null) {
                            callback();
                        }
                    }
                })
            },
            shopChangeGetData(evt) {
                let shop_id = evt.currentTarget.value
                let img = $(evt.currentTarget).find(':selected').data('img')
                $("#shop_image").attr('src', img)
                this.shopDataFetch(shop_id);
            },
            salesIdDateFormat() {
                let d = new Date();
                let str = `${d.getFullYear()}${d.getMonth()+1}${d.getDate()}-${String(Date.now()).substring(6)}`;
                return str;
            },
            populateDraftedOrder() {
                let productItems = []
                this.draftedOrder = {!! json_encode((isset($order) && !is_null($order)) ? $order->toArray() : [], JSON_HEX_TAG) !!};
                if (typeof this.draftedOrder === "object" && ("id" in this.draftedOrder)) {
                    this.customer = this.draftedOrder.customer;
                    this.shop_id = this.draftedOrder.shop_id;
                    this.order_id = this.draftedOrder.order_number;
                    this.shopDataFetch(this.shop_id, () => {
                        if ("order_detail" in this.draftedOrder) {
                            if (this.draftedOrder.order_detail.length > 0) {
                                this.draftedOrder.order_detail.forEach(item => {
                                    this.draftedAddCart(item);
                                });
                            }
                        }
                    })
                    
                } else {
                    this.order_id  = this.salesIdDateFormat()
                }
            }
        }
    })
    
    $(function(){
        $( "#customer_name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: `{{route('getCustomers')}}`,
                    data: {
                        term: request.term
                    },
                    success: function( res ) {
                        if (res.status) {
                            if(res.data.customers.length === 0) {
                                var result = [
                                    {
                                        customer_name: request.term, 
                                        customer_address: "Sorry nothing found, We will save it as a new contact"
                                    }
                                ];
                                response(result);
                            }
                            else{
                                response(res.data.customers);
                            }
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