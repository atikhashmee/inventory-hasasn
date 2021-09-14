@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Challan Details</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('admin.challans.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('admin.challans.show_fields')
                </div>
            </div>
            <div class="card-footer">
                <a href="{{url('print-challan-conditioned/'.$challan->id)}}" target="_blank" class="btn btn-primary float-right" >
                    <i class="fas fa-print"></i> Print
                </a>
            </div>
        </div>
    </div>
@endsection
