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
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
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
    let stock_app = new Vue({
        el: '#purchase_form',
        data: {
            product: null,
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
</script>
@endpush
