@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create Sale Return</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3" id="sell_return">

        @include('adminlte-templates::common.errors')
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Sale Number</label>
                            <input type="text" name="order_number" v-model="order_number" class="form-control" placeholder="Sale Number">
                            <span v-if="errors?.order_id?.length > 0" role="alert"  class="text-danger">@{{ errors.order_id[0] }}</span>
                        </div>
                        <div class="form-group">
                            <span v-if="error" role="alert"  class="text-danger">@{{ error }}</span>
                            <span v-if="msg" role="alert"  class="text-success">@{{ msg }}</span>
                        </div>
                        <button class="btn btn-success" type="button" @click="queryData()">Check Validity</button>
                        <a class="btn btn-default" href="{{ route('admin.order.return') }}">Back</a>
                    </div>
        
                    <div class="card-footer">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Product Name</th>
                                <th>Warenty Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in order_items">
                                <td>@{{item.product_id}}</td>
                                <td>@{{item.product_name}}</td>
                                <td>@{{item.time_left}}</td>
                            </tr>
                        </tbody>
                    </table>
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
   
        let returnApp = new Vue({
            el: '#sell_return',
            data: {
                order_items: [],
                order_number: '',
                errors: {},
                error: null,
                msg: '',
            },
            mounted() {
            },
            methods: {
                queryData() {
                    this.order_items = [];
                    this.error = null,
                    this.msg =  '',
                    fetch(`{{route('admin.warenty.check.validation')}}`,  {
                        method: 'POST',
                        headers: {
                            "X-CSRF-TOKEN": `{{csrf_token()}}`,
                            'Content-Type': 'application/json'
                        }, 
                        body: JSON.stringify({
                            order_number: this.order_number
                        })
                    }).then(res=>res.json())
                    .then(res=>{
                        if (res.status) {
                             this.msg = res.msg;
                             this.order_items = res.data;
                        } else {
                            if (res.errors) {
                                this.errors = res.errors
                            }

                            if (res.error) {
                                this.error = res.error
                            }
                        }
                    })
                
                }
            }
        })
    </script>
@endpush