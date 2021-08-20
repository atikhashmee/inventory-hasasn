<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Website Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('website_url', 'Website Url:') !!}
    {!! Form::text('website_url', null, ['class' => 'form-control']) !!}
</div>

<!-- Contact Person Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact_person_name', 'Contact Person Name:') !!}
    {!! Form::text('contact_person_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Contact Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact_email', 'Contact Email:') !!}
    {!! Form::text('contact_email', null, ['class' => 'form-control']) !!}
</div>

<!-- Contact Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('contact_phone', 'Contact Phone:') !!}
    {!! Form::text('contact_phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Country Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('country_id', 'Country Id:') !!}
    {!! Form::select('country_id', $countryItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Address Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
</div>