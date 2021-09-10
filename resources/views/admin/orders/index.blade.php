@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.orders.index')}}" method="GET">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div></div>
                        <div class="d-flex" style="flex-basis: 40%">
                            <select name="shop_id" id="shop_id" class="form-control mr-2">
                                <option value="">Select a shop</option>
                                @foreach ($shops as $shop)
                                    <option value="{{$shop->id}}">{{$shop->name}}</option>
                                @endforeach
                            </select>
                            <input type="search" name="search" class="form-control" placeholder="Order Number, Customer Name" >
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
                @include('admin.orders.table')

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

