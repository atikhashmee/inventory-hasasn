<!-- Shop Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shop_id', 'Shop:') !!}
    {!! Form::select('shop_id', $shopItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Customer Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customer_id', 'Customer:') !!}
    {!! Form::select('customer_id', $customerItems, null, ['class' => 'form-control custom-select select2']) !!}
</div>

<!-- Challan Type -->
<div class="form-group col-sm-6">
    {!! Form::label('challan_type', 'Challan Type:') !!}
    {!! Form::select('challan_type', $challan_types, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Product Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('product_type', 'Product Type:') !!}
    {!! Form::text('product_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Quantity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('quantity', 'Quantity:') !!}
    {!! Form::number('quantity', null, ['class' => 'form-control']) !!}
</div>

<!-- Unit Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('unit_id', 'Unit:') !!}
    {!! Form::select('unit_id', $unitItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Total Payable Field -->
<div class="form-group col-sm-6" id="total_payable_wrapper">
    {!! Form::label('total_payable', 'Total Payable:') !!}
    {!! Form::text('total_payable', null, ['class' => 'form-control']) !!}
</div>

<!-- Challan Note Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('challan_note', 'Challan Note:') !!}
    {!! Form::textarea('challan_note', null, ['class' => 'form-control']) !!}
</div>