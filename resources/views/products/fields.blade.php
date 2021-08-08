<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Slug Field -->
<div class="form-group col-sm-6">
    {!! Form::label('slug', 'Slug:') !!}
    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
</div>

<!-- Sku Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sku', 'Sku:') !!}
    {!! Form::text('sku', null, ['class' => 'form-control']) !!}
</div>

<!-- Category Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Category Id:') !!}
    {!! Form::select('category_id', $categoryItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Brand Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('brand_id', 'Brand Id:') !!}
    {!! Form::select('brand_id', $brandItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Supplier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('supplier_id', 'Supplier Id:') !!}
    {!! Form::select('supplier_id', $supplierItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Menufacture Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('menufacture_id', 'Menufacture Id:') !!}
    {!! Form::select('menufacture_id', $menufactureItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Warehouse Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('warehouse_id', 'Warehouse Id:') !!}
    {!! Form::select('warehouse_id', $ware_houseItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Feature Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('feature_image', 'Feature Image:') !!}
    <div class="input-group">
        <div class="custom-file">
            {!! Form::file('feature_image', ['class' => 'custom-file-input']) !!}
            {!! Form::label('feature_image', 'Choose file', ['class' => 'custom-file-label']) !!}
        </div>
    </div>
</div>
<div class="clearfix"></div>
