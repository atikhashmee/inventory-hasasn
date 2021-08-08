<!-- Ware House Name Field -->
<div class="col-sm-12">
    {!! Form::label('ware_house_name', 'Ware House Name:') !!}
    <p>{{ $wareHouse->ware_house_name }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $wareHouse->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $wareHouse->updated_at }}</p>
</div>

