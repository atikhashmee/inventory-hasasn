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
            customer: {
                customer_name: null,
                customer_email: null,
                customer_phone: null,
                customer_address: null,
            },
            user_role: null,
        },
        mounted() {
            this.order_id  = this.salesIdDateFormat()
            this.user_role = document.querySelector('#user_role').value;
            if (this.user_role !== 'admin') {
                this.shop_id = document.querySelector('#shop_id').value;
                this.shopDataFetch(this.shop_id)
            }
        },
        computed: {
            subTotalValue() {
                this.subtotal =  this.product_lists.reduce((total, item)=> total += Number(item.quantity) * Number(item.price), 0)
                return  this.subtotal;
            }
        },
        watch: {
            shop_id(oldval, newval) {
               if (oldval) {
                   this.product_lists = []
               } 
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
            submitOrder() {
                let orderObj = {};
                orderObj.items = {...this.product_lists}
                orderObj.order_number = this.order_id;
                orderObj.date = this.order_date;
                orderObj.sale_type = this.sale_type;
                orderObj.customer_name = this.customer.customer_name;
                orderObj.customer_address = this.customer.customer_address;
                orderObj.customer_phone = this.customer.customer_phone;
                orderObj.customer_email = this.customer.customer_email;
                orderObj.subtotal = this.subtotal;
                orderObj.discount = this.discount;
                orderObj.shop_id = this.shop_id;
                orderObj.note = this.note;
                orderObj.challan_note = this.challan_note;
                orderObj.payment_amount = this.payment_amount;
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
                    if (res.status) {
                        if (this.user_role === 'admin') {
                            window.location.href= `{{url('admin/orders/')}}/${res.data.id}`;
                        } else if(this.user_role === 'staff') {
                            window.location.href= `{{url('user/order/')}}/${res.data.id}`;
                        }
                    }
                })
            },
            shopDataFetch(shop_id) {
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