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

<!-- Product Cost Field -->
<div class="col-sm-12">
    {!! Form::label('product_cost', 'Product Cost:') !!}
    <p>{{ $product->product_cost }}</p>
</div>

<!-- Selling Price Field -->
<div class="col-sm-12">
    {!! Form::label('selling_price', 'Selling Price:') !!}
    <p>{{ $product->selling_price }}</p>
</div>

<!-- Category Id Field -->
<div class="col-sm-12">
    {!! Form::label('category_id', 'Category Id:') !!}
    <p>{{ $product->category_id }}</p>
</div>

<!-- Origin Field -->
<div class="col-sm-12">
    {!! Form::label('origin', 'Origin:') !!}
    <p>{{ $product->origin }}</p>
</div>

<!-- Brand Id Field -->
<div class="col-sm-12">
    {!! Form::label('brand_id', 'Brand Id:') !!}
    <p>{{ $product->brand_id }}</p>
</div>

<!-- Menufacture Id Field -->
<div class="col-sm-12">
    {!! Form::label('menufacture_id', 'Menufacture Id:') !!}
    <p>{{ $product->menufacture_id }}</p>
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

