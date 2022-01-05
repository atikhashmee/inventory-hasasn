@extends('layouts.user')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Customer</h1>
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
                <div class="container mt-5" id="order_new">
                    <div class="row">
                        <div class="col-md-12">
                            @if ($customer)
                            <form action="{{route('admin.customers.update', ['customer'=> $customer])}}" method="POST" autocomplete="off">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Name <span class="text-danger">*</span> </label>
                                            <input type="text" 
                                            autocomplete="off" 
                                            name="customer_name" 
                                            id="customer_name" 
                                            class="form-control" 
                                            value="{{old('customer_name', $customer->customer_name)}}"
                                            placeholder="Enter Customer Name" /> 
                                            @error('customer_name')
                                                <strong class="text-danger">{{$message}}</strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Email <span class="text-danger">*</span></label>
                                            <input type="text" 
                                            name="customer_email" 
                                            class="form-control" 
                                            value="{{old('customer_email', $customer->customer_email)}}"
                                            placeholder="Enter Customer Email" /> 
                                            @error('customer_email')
                                                <strong class="text-danger">{{$message}}</strong>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Customer Phone</label>
                                            <input type="text" 
                                            name="customer_phone" 
                                            class="form-control" 
                                            value="{{old('customer_phone', $customer->customer_phone)}}"
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
                                                {{old('customer_address', $customer->customer_address)}}
                                            </textarea>
                                            @error('customer_address')
                                                <strong class="text-danger">{{$message}}</strong>
                                            @enderror
                                        </div>
                                   </div>
                               </div>
                                <a href="{{route('admin.customers.index')}}" class="btn btn-default float-left" type="submit">Go To Lists</a>
                                <button class="btn btn-primary float-right" type="submit">Update</button>
                            </form>
                            @else
                            <div class="text-center">Item not found</div>
                            @endif
                            
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
