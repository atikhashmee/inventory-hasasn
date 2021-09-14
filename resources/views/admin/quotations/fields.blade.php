<!-- Shop Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shop_id', 'Shop:') !!}
    {!! Form::select('shop_id', $shopItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Recipient Field -->
<div class="form-group col-sm-6">
    {!! Form::label('recipient', 'Recipient:') !!}
    {!! Form::text('recipient', null, ['class' => 'form-control']) !!}
</div>

<!-- Recipient Address Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('recipient_address', 'Recipient Address:') !!}
    {!! Form::textarea('recipient_address', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', 'Date:') !!}
    {!! Form::date('date', null, ['class' => 'form-control']) !!}
</div>

<!-- Subject Field -->
<div class="form-group col-sm-6">
    {!! Form::label('subject', 'Subject:') !!}
    {!! Form::text('subject', null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('notes', 'Notes:') !!}
    {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
</div>