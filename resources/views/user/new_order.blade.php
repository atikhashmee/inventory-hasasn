@extends('layouts.user')

@section('content')
    <div class="container mt-5" id="order_new">
        <div class="row">
            <div class="col-md-12">
                @include('shared.order_form')
            </div>
        </div>
    </div>
@endsection

@include('shared.order_form_script')