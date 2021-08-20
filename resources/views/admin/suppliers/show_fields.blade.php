<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $supplier->name }}</p>
</div>

<!-- Website Url Field -->
<div class="col-sm-12">
    {!! Form::label('website_url', 'Website Url:') !!}
    <p>{{ $supplier->website_url }}</p>
</div>

<!-- Contact Person Name Field -->
<div class="col-sm-12">
    {!! Form::label('contact_person_name', 'Contact Person Name:') !!}
    <p>{{ $supplier->contact_person_name }}</p>
</div>

<!-- Contact Email Field -->
<div class="col-sm-12">
    {!! Form::label('contact_email', 'Contact Email:') !!}
    <p>{{ $supplier->contact_email }}</p>
</div>

<!-- Contact Phone Field -->
<div class="col-sm-12">
    {!! Form::label('contact_phone', 'Contact Phone:') !!}
    <p>{{ $supplier->contact_phone }}</p>
</div>

<!-- Country Id Field -->
<div class="col-sm-12">
    {!! Form::label('country_id', 'Country Id:') !!}
    <p>{{ $supplier->country_id }}</p>
</div>

<!-- Address Field -->
<div class="col-sm-12">
    {!! Form::label('address', 'Address:') !!}
    <p>{{ $supplier->address }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $supplier->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $supplier->updated_at }}</p>
</div>

