@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Challans</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('admin.challans.create') }}">
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
                <form action="{{route("admin.challans.index")}}" class="d-flex flex-row-reverse">
                    <div class="form-group">
                        <label for="">&nbsp;</label>
                        <button type="submit" class="btn btn-success">Filter</button>
                    </div>
                    <div class="form-group mr-2">
                        <select name="shop_id" id="shop_id" class="form-control select2">
                            <option value="">Select a shop</option>
                            @foreach ($shops as $shop)
                                <option value="{{$shop->id}}">{{$shop->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <select name="customer_id" id="customer_id" class="form-control select2">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->customer_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        {!! Form::select('challan_type', App\Models\Challan::$challan_types, null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group mr-2">
                        {!! Form::select('status', App\Models\Challan::$challan_status, null, ['class' => 'form-control']) !!}
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                @include('admin.challans.table')

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

