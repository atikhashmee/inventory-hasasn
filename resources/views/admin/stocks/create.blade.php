@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create New Purchase</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'admin.stocks.store']) !!}

            <div class="card-body" id="purchase_form">

                <div class="row">
                    @include('admin.stocks.fields')
                </div>

            </div>

            <div class="card-footer">
                {!! Form::button('Save', ['class' => 'btn btn-primary', 'id' => 'submit_button']) !!}
                <a href="{{ route('admin.stocks.index') }}" class="btn btn-default">Cancel</a>
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
    document.getElementById('sku').value = makeRandomSku(6)
    let stock_app = new Vue({
        el: '#purchase_form',
        data: {
            product: null,
        },
        computed: {
            oldPrice() {
                let oldprice = 0;
                if (this.product && this.product.all_price) {
                    let allPrice = this.product.all_price.split(',')
                    oldprice = allPrice[allPrice.length - 1]
                }
                return oldprice
            }
        },
        mounted() {
        },
        methods: {
            getResource(id) {
                let url = `{{url("admin/get_product_detail")}}/${id}`
                fetch(url)
                .then(res=>res.json())
                .then(res=>{
                    if (res.status) {
                        this.product = {...res.data}
                    } else {
                        this.product = null
                    }
                })
            }
        }
    })
    $('#product_id').on('select2:select', function (e) {
        stock_app.product = null;
        if (e.currentTarget.value) {
            stock_app.getResource(e.currentTarget.value)
        }
    });

    $("#submit_button").on('click', evt => {
        $.showConfirm({
            modalClass: 'mt-50',
            title: "Are you sure?", 
            body: "Please Recheck if all the information is correct before submit, once you submit, you can not modify or delete it", 
            modalDialogClass: "modal-dialog-centered",
            textTrue: "Confirm", 
            textFalse: "Discard",
            onSubmit: function (result) {
                if (result) {
                    $(evt.currentTarget).closest('form').submit()
                }
            },
            onDispose: function () {
                console.log("The confirm dialog vanished",  )
            }
        })
    })
</script>
@endpush
