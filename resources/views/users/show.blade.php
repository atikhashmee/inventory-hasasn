@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            User
        </h1>
    </section>
    <div class="content">
        <div class="card card-primary">
            <div class="card-body">
                @include('users.show_fields')
                <a href="{!! route('admin.users.index') !!}" class="btn btn-default">Back</a>
            </div>
        </div>
    </div>
@endsection
