@extends('layouts.app')

@push('page_css')
    <style>
        .input-form-group {
           margin-right: 10px;
        }
    </style>
@endpush
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
                <form action="{{ route('admin.products.index') }}" method="get">
                    <div class="d-flex flex-wrap">
                        <div class="form-group input-form-group">
                            <select name="origin_id" class="form-control select2" style="max-width:100%;">
                                <option value="">Select Origin</option>
                                @foreach ($countries as $country_id => $country)
                                    <option value="{{$country_id}}" @if(Request::get('origin_id') == $country_id) selected @endif>{{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="form-group input-form-group">
                            <select name="menufacture_id" class="form-control select2" style="width:100%;">
                                <option value="">Select Manufacture</option>
                                @foreach ($menufactures as $menufacture_id => $menufacture)
                                    <option value="{{$menufacture_id}}" @if(Request::get('menufacture_id') == $menufacture_id) selected @endif>{{$menufacture}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group input-form-group">
                            <select name="brand_id" class="form-control select2" style="width:100%;">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand_id => $brand)
                                    <option value="{{$brand_id}}" @if(Request::get('brand_id') == $brand_id) selected @endif>{{$brand}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group input-form-group">
                            <select name="category_id" id="category_id" class="form-control custom-select select2" style="max-width:90%; width: 300px">
                                <option value="">Select a category</option>
                                @if (count($categoryItems) > 0)
                                    @foreach ($categoryItems as $item)
                                        <option @if(isset($product) && $product->category_id == $item['id']) selected @endif value="{{$item['id']}}">{{$item['name']}}</option>
                                        @if (count($item['nested']) > 0)
                                            @foreach ($item['nested'] as $child)
                                                <option @if(isset($product) && $product->category_id == $child['id']) selected @endif value="{{$child['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;{{$child['name']}}</option>
                                                @if (count($child['nested']) > 0)
                                                    @foreach ($child['nested'] as $childItem)
                                                        <option @if(isset($product) && $product->category_id == $childItem['id']) selected @endif value="{{$childItem['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$childItem['name']}}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group input-form-group">
                            <input type="text"  class="form-control" name="search" style="width:100%;" placeholder="Search....">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"> <i class="fa fa-filter">Filter</i> </button>
                        </div>
                    </div>
                </form>
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

