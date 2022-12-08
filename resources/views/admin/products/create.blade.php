@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create Product</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'admin.products.store', 'files' => true]) !!}

            <div class="card-body">
                <div class="row">
                    @include('admin.products.fields')
                </div>
                <div class="row">
                    <div class="col-md-12">
                            <a  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                              Advance Options
                            </a>
                            <input type="hidden" name="distribution_required" id="distribution_required" value="0">
                          <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Suppliers</label>
                                            <select class="form-control" name="supplier_id" id="supplier_id">
                                                <option value="">Select Supplier</option>
                                                @foreach ($supplierItems as $sup_id => $sup_name)
                                                    <option value="{{$sup_id}}">{{$sup_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Ware House</label>
                                            <select class="form-control" name="warehouse_id" id="warehouse_id">
                                                <option value="">Select warehouse</option>
                                                @foreach ($ware_houseItems as $ware_id => $ware_name)
                                                    <option value="{{$ware_id}}">{{$ware_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Purchase Quantity</label>
                                            <input type="text" class="form-control" name="purchase_quantity">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Purchase Price</label>
                                            <input type="text" class="form-control" name="purchase_price" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Selling Price</label>
                                            <input type="text" class="form-control" name="selling_price" >
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Select a shop</label>
                                            <select class="form-control" name="shop_id" id="shop_id">
                                                <option value="">Select Shop</option>
                                                @foreach ($shopItems as $shp_id => $shp_name)
                                                    <option value="{{$shp_id}}">{{$shp_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Distribute Quantity</label>
                                            <input type="text" class="form-control" name="stock_quantity">
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('admin.products.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
  
@endsection
@push('page_scripts')
    <script>
        $('#collapseExample').on('shown.bs.collapse', function () {
            $("#distribution_required").val(1)
        })
        
        $('#collapseExample').on('hidden.bs.collapse', function () {
            $("#distribution_required").val(0)
        })
    </script>
@endpush
