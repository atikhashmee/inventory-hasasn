@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Customers</h1>
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
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div></div>
                    <div class="d-flex">
                        <a href="{{route('admin.customers.create')}}" class="btn btn-primary">Add new <i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <div class="card-header">
                    <form action="{{ route('admin.customers.index') }}" method="get">
                        <div class="d-flex flex-wrap">
                            <div class="form-group input-form-group">
                                <select name="customer_type" class="form-control select2" style="width:100%;">
                                    <option value="">Customer Types</option>
                                    @foreach ($customer_types as $c_type)
                                        <option @if(Request::get('customer_type') == $c_type) selected @endif>{{$c_type}}</option>
                                    @endforeach
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
                </div>
                @include('admin.customers.table')

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

