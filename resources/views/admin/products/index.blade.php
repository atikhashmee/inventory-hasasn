@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Products</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('admin.products.create') }}">
                        Add New
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <form action="{{ route('admin.products.index') }}" method="get">
                            <div class="d-flex flex-row-reverse">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"> <i class="fa fa-filter">Filter</i> </button>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="product_id" class="form-control select2">
                                        <option value="">Select Category</option>
                                        {{-- @foreach ($products as $product)
                                            <option value="{{$product->id}}" @if(Request::get('product_id') == $product->id) selected @endif>{{$product->name}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="brand_id" class="form-control select2">
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand_id => $brand)
                                            <option value="{{$brand_id}}" @if(Request::get('brand_id') == $brand_id) selected @endif>{{$brand}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="menufacture_id" class="form-control select2">
                                        <option value="">Select Manufacture</option>
                                        @foreach ($menufactures as $menufacture_id => $menufacture)
                                            <option value="{{$menufacture_id}}" @if(Request::get('menufacture_id') == $menufacture_id) selected @endif>{{$menufacture}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <select name="origin_id" class="form-control select2">
                                        <option value="">Select Origin</option>
                                        @foreach ($countries as $country_id => $country)
                                            <option value="{{$country_id}}" @if(Request::get('origin_id') == $country_id) selected @endif>{{$country}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <div class="card-body p-0">
                @include('admin.products.table')

                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{$products->withQueryString()->links()}}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

