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

<!-- Old Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('old_price', 'Old Price:') !!}
    {!! Form::text('old_price', null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price:') !!}
    {!! Form::text('price', null, ['class' => 'form-control']) !!}
</div>

<!-- Selling Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('selling_price', 'Selling Price:') !!}
    {!! Form::text('selling_price', null, ['class' => 'form-control']) !!}
</div>

<!-- Quantity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('quantity', 'Quantity:') !!}
    {!! Form::number('quantity', null, ['class' => 'form-control']) !!}
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
    <select name="category_id" id="category_id" class="form-control custom-select select2">
        <option value="">Select a category</option>
        @if (count($categoryItems) > 0)
            @foreach ($categoryItems as $item)
                <option @if(isset($product) && $product->category_id == $item['id']) selected @endif value="{{$item['id']}}">{{$item['name']}}</option>
                @if (count($item['nested']) > 0)
                    @foreach ($item['nested'] as $child)
                        <option @if(isset($product) && $product->category_id == $child['id']) selected @endif value="{{$child['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;{{$child['name']}}</option>
                        @if (count($child['nested']) > 0)
                            @foreach ($child['nested'] as $childItem)
                                <option @if(isset($product) && $product->category_id == $childItem['id']) selected @endif value="{{$childItem['id']}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$childItem['name']}}</option>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif
    </select>
</div>


<!-- Brand Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('brand_id', 'Brand:') !!}
    {!! Form::select('brand_id', $brandItems, null, ['class' => 'form-control custom-select select2']) !!}
</div>


<!-- Supplier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('supplier_id', 'Supplier:') !!}
    {!! Form::select('supplier_id', $supplierItems, null, ['class' => 'form-control custom-select select2']) !!}
</div>


<!-- Menufacture Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('menufacture_id', 'Menufacture:') !!}
    {!! Form::select('menufacture_id', $menufactureItems, null, ['class' => 'form-control custom-select select2']) !!}
</div>


<!-- Warehouse Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('warehouse_id', 'Warehouse:') !!}
    {!! Form::select('warehouse_id', $ware_houseItems, null, ['class' => 'form-control custom-select select2']) !!}
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
