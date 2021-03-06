@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create Challan</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'admin.challans.store']) !!}

            <div class="card-body">
                <div class="row">
                    @include('admin.challans.fields')
                </div>


            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('admin.challans.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
@push('page_scripts')
   <script>
      $('#challan_type').on('change', function(evt) {
        let select = evt.currentTarget;
         challanTypeHideShow(select);
      }) 
      function challanTypeHideShow(dom = null) {
        let select = dom ===null ? $('#challan_type') : dom; 
        if ($(select).val() == 'normal') {
            $('#total_payable_wrapper').hide()
        } else {
            $('#total_payable_wrapper').show()
        }
      }
    challanTypeHideShow()
   </script> 
@endpush
