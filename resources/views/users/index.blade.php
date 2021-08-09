@extends('layouts.app')

@section('content')
    <section class="content-header d-flex justify-content-between">
        <h1 class="pull-left">Users</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.users.create') !!}">Add New</a>
        </h1>
    </section>
    <div class="content">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                    @include('users.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

