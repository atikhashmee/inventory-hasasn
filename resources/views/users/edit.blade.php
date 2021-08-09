@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            User
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="card card-primary">
           <div class="card-body">
                {!! Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'patch']) !!}
                    @include('users.fields')
                {!! Form::close() !!}
           </div>
       </div>
   </div>
@endsection