<!-- Shop Id Field -->
<div class="col-sm-12">
    {!! Form::label('shop_id', 'Shop Id:') !!}
    <p>{{ $quotation->shop_id }}</p>
</div>

<!-- Recipient Field -->
<div class="col-sm-12">
    {!! Form::label('recipient', 'Recipient:') !!}
    <p>{{ $quotation->recipient }}</p>
</div>

<!-- Recipient Address Field -->
<div class="col-sm-12">
    {!! Form::label('recipient_address', 'Recipient Address:') !!}
    <p>{{ $quotation->recipient_address }}</p>
</div>

<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('date', 'Date:') !!}
    <p>{{ $quotation->date }}</p>
</div>

<!-- Subject Field -->
<div class="col-sm-12">
    {!! Form::label('subject', 'Subject:') !!}
    <p>{{ $quotation->subject }}</p>
</div>

<!-- Notes Field -->
<div class="col-sm-12">
    {!! Form::label('notes', 'Notes:') !!}
    <p>{{ $quotation->notes }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $quotation->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $quotation->updated_at }}</p>
</div>

