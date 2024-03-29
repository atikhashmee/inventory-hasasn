@extends('layouts.app')
@push('page_css')
    <style>
        .each-item{
            border: 1px solid red;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
@endpush
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create Quotation</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card" id="quotationapp">

            {!! Form::open(['route' => 'admin.quotations.store']) !!}

            <div class="card-body">
                @include('admin.quotations.fields')
            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('admin.quotations.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
@push('third_party_scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
@endpush
@push('page_scripts')
    <script>
        function initLibs() {
            setTimeout(function () {
                $('.select2').select2({
                    width: '100%',
                    placeholder: 'Product',
                });
                $('.product_id_class_name').on('select2:select', function (e) {
                    let item_id = $(e.currentTarget).closest('.rowclass').data('id');
                    let product_id = e.currentTarget.value
                    let product_item = quotationApp.products.find(it => it.id == product_id)
                    /* check if the product is already added in the cart */
                    let ifExist = quotationApp.quotation_items.find(dt=>dt.product_id == product_item.id)
                    if (ifExist) {
                        alert('Already added in the list')
                        quotationApp.quotation_items = quotationApp.quotation_items.filter(cartitem=>cartitem.product_id!==null)
                        return;
                    } else {
                        quotationApp.quotation_items = quotationApp.quotation_items.map(item=>{
                            if (item.item_id === item_id) {
                                item.product_id = product_item.id
                                item.product_name = product_item.name
                                item.brand_name = product_item.brand.name
                                item.quantity = 0
                                item.model = ''
                                item.unit =  0
                                item.origin = product_item.origin.name
                                item.unit_price = product_item.selling_price
                                item.total_price =  (product_item.selling_price * item.quantity)
                            }
                            return item;
                        })
                    }
                });
            }, 10);
        }
        let quotationApp = new Vue({
            el: '#quotationapp',
            data:{
                products: [],
                quotation_items: [],
            },
            mounted() {
                this.quotation_items.push({
                        item_id : Date.now() + 1,
                        product_id: null,
                        product_name: null,
                        brand_name: null,
                        // model: null,
                        origin: null, 
                        quantity: null,
                        unit_price: null,
                        total_price: null,
                        unit: 0,
                        total_price_calculated: () => {
                            this.quantity * this.unit_price
                        }
                    })
                initLibs()
                this.products = {!! json_encode($products, JSON_HEX_TAG) !!};
            },
            updated() {
               // console.log(this.quotation_items);
            },
            methods: {
                additem() {
                    this.quotation_items.push({
                        item_id : Date.now() + 1,
                        product_id: null,
                        product_name: null,
                        brand_name: null,
                        // model: null,
                        origin: null, 
                        quantity: null,
                        unit_price: null,
                        total_price: null,
                        unit: 0,
                        total_price_calculated: () => {
                            this.quantity * this.unit_price
                        }
                    })
                    initLibs()
                },
                changeCalculation(qitem) {
                    if (this.quotation_items.length > 0) {
                        this.quotation_items = this.quotation_items.map(element => {
                            if (qitem.item_id == element.item_id) {
                                element = {...qitem}
                                element.total_price = Number(element?.unit_price) * Number(element?.quantity);
                            }
                            return element;
                        });
                    }
                }
            }
        })
        $(document).ready(function() {
            let defaultText = `
            <strong>Terms & Condition</strong>
            <p>1. Delivery: From ready stock</p>
            <p>2. Payment: By cash, cheque</p>
            <p>3. Validity: Our Quotation is valid for 1 month</p>
            <p>4. Vat & Tax: Our price <b>does not include</b> local vat or taxes</p>
            <p>5. Warranty: 1 year free service warranty will be provided</p>`;
            $('#summernoteq').summernote('code', defaultText);
        });
    </script>
@endpush