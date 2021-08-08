<!-- Product Id Field -->
<div class="col-sm-12">
    {!! Form::label('product_id', 'Product Id:') !!}
    <p>{{ $stock->product_id }}</p>
</div>

<!-- Warehouse Id Field -->
<div class="col-sm-12">
    {!! Form::label('warehouse_id', 'Warehouse Id:') !!}
    <p>{{ $stock->warehouse_id }}</p>
</div>

<!-- Sku Field -->
<div class="col-sm-12">
    {!! Form::label('sku', 'Sku:') !!}
    <p>{{ $stock->sku }}</p>
</div>

<!-- Old Price Field -->
<div class="col-sm-12">
    {!! Form::label('old_price', 'Old Price:') !!}
    <p>{{ $stock->old_price }}</p>
</div>

<!-- Price Field -->
<div class="col-sm-12">
    {!! Form::label('price', 'Price:') !!}
    <p>{{ $stock->price }}</p>
</div>

<!-- Selling Price Field -->
<div class="col-sm-12">
    {!! Form::label('selling_price', 'Selling Price:') !!}
    <p>{{ $stock->selling_price }}</p>
</div>

<!-- Quantity Field -->
<div class="col-sm-12">
    {!! Form::label('quantity', 'Quantity:') !!}
    <p>{{ $stock->quantity }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $stock->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $stock->updated_at }}</p>
</div>

