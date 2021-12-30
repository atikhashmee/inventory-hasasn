@extends('layouts.app')

@section('content')
    @php
        date_default_timezone_set('asia/dhaka');
    @endphp
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Sale</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        <div class="clearfix"></div>
        @include('shared.order_form')
    </div>

@endsection
@include('shared.order_form_script')
