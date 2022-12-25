@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create Transaction</h1>
                </div>
            </div>
        </div>
    </section>
    <div class="content px-3">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="card">
            {!! Form::open(['route' => 'admin.transactions.store']) !!}
            <div class="card-body">
                <div class="row">
                    @include('admin.transactions.fields')
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-default">Back to Lists</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        let customers = {!! json_encode(count($customers) > 0 ? $customers->toArray() : [], JSON_HEX_TAG) !!}
        function changeCustomer(evt) {
            let $evt = $(evt)
            let invoiceDom = '<option value="">Select Invoice</option>';
            if (customers.length > 0) {
                customers.forEach(element => {
                    if (Number($evt.val()) === Number(element.id)) {
                        $("#payable").val(Number(element.total_withdraw) - Number(element.total_deposit))
                        if (("orders" in element) && element.orders.length > 0) {
                            element.orders.forEach(orderItem => {
                                invoiceDom += `<option value="${orderItem.id}">${orderItem.order_number}</option>`;
                            })
                        }
                        $("#order_id").append(invoiceDom);
                        return;
                    }
                });
            }
        }
    </script>
@endpush
