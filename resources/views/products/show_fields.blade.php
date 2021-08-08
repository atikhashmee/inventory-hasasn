<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $product->name }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $product->description }}</p>
</div>

<!-- Slug Field -->
<div class="col-sm-12">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $product->slug }}</p>
</div>

<!-- Sku Field -->
<div class="col-sm-12">
    {!! Form::label('sku', 'Sku:') !!}
    <p>{{ $product->sku }}</p>
</div>

<!-- Category Id Field -->
<div class="col-sm-12">
    {!! Form::label('category_id', 'Category Id:') !!}
    <p>{{ $product->category_id }}</p>
</div>

<!-- Warehouse Id Field -->
<div class="col-sm-12">
    {!! Form::label('warehouse_id', 'Warehouse Id:') !!}
    <p>{{ $product->warehouse_id }}</p>
</div>

<!-- Feature Image Field -->
<div class="col-sm-12">
    {!! Form::label('feature_image', 'Feature Image:') !!}
    <p>{{ $product->feature_image }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $product->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $product->updated_at }}</p>
</div>

