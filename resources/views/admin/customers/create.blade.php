@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Customer</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body">
                <div class="container mt-5" id="order_new">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('admin.customers.store')}}" method="POST" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Name <span class="text-danger">*</span> </label>
                                            <input type="text" 
                                            autocomplete="off" 
                                            name="customer_name" 
                                            id="customer_name" 
                                            class="form-control" 
                                            value="{{old('customer_name')}}"
                                            placeholder="Enter Customer Name" /> 
                                            @error('customer_name')
                                                <strong class="text-danger">{{$message}}</strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Email</label>
                                            <input type="text" 
                                            name="customer_email" 
                                            class="form-control" 
                                            value="{{old('customer_email')}}"
                                            placeholder="Enter Customer Email" /> 
                                            @error('customer_email')
                                                <strong class="text-danger">{{$message}}</strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Phone <span class="text-danger">*</span></label>
                                            <input type="text" 
                                            name="customer_phone" 
                                            class="form-control" 
                                            value="{{old('customer_phone')}}"
                                            placeholder="Enter Customer Phone" /> 
                                            @error('customer_phone')
                                                <strong class="text-danger">{{$message}}</strong>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                               <div class="row">
                                   <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Customer Address</label>
                                            <textarea name="customer_address" id="customer_address" class="form-control" cols="30">
                                                {{old('customer_address')}}
                                            </textarea>
                                            @error('customer_address')
                                                <strong class="text-danger">{{$message}}</strong>
                                            @enderror
                                        </div>
                                   </div>
                               </div>
                               <div class="row">
                                   <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Customer Type</label>
                                            <select name="customer_type" id="customer_type" class="form-control">
                                                <option value="">Select a type</option>
                                                <option @if(old('customer_type') == "Vendors") selected @endif>Vendors</option>
                                                <option @if(old('customer_type') == "Hospitals") selected @endif>Hospitals</option>
                                                <option @if(old('customer_type') == "Doctors") selected @endif>Doctors</option>
                                                <option @if(old('customer_type') == "District") selected @endif>District</option>
                                            </select>
                                            @error('customer_type')
                                                <strong class="text-danger">{{$message}}</strong>
                                            @enderror
                                        </div>
                                   </div>
                               </div>
                                <a href="{{route('admin.customers.index')}}" class="btn btn-default float-left" type="submit">Go Back</a>
                                <button class="btn btn-primary float-right" type="submit">Create</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
@push('third_party_scripts')
   
@endpush
@push('page_scripts')

@endpush
