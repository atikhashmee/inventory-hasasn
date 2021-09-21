@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quotation Details</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('admin.quotations.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @include('admin.quotations.show_fields')
                    </div>
                    <div class="card-footer">
                        <a href="#" target="_blank" class="btn btn-primary float-right">
                            <i class="fas fa-print"></i> Print
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
       
    </div>
@endsection
