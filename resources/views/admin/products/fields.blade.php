<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<!-- Sku Field -->
<div class="form-group col-sm-6">
    <div class="d-flex justify-content-between align-items-center">
        <div style="flex-basis: 80%">
            {!! Form::label('code', 'Code:') !!}
            {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'MED-34543']) !!}
        </div>
        <div class="d-flex flex-column" style="flex-basis: 20%">
            <label for="">&nbsp;</label>
            <button class="btn btn-primary"  type="button" onclick="document.getElementById('code').value ='MED-'+makeRandomSku(6)">Generate</button>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Product Cost Field -->
<div class="form-group col-sm-6">
    {!! Form::label('product_cost', 'Product Cost: (Current Purchase Price)') !!}
    {!! Form::text('product_cost', null, ['class' => 'form-control']) !!}
</div>

<!-- Selling Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('selling_price', 'Selling Price: (Current Selling Price)') !!}
    {!! Form::text('selling_price', null, ['class' => 'form-control']) !!}
</div>

<!-- Category Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Category:') !!}
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


<!-- Origin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('origin', 'Origin:') !!}
    {!! Form::select('origin', $countryItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Brand Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('brand_id', 'Brand:') !!}
    {!! Form::select('brand_id', $brandItems, null, ['class' => 'form-control custom-select']) !!}
</div>


<!-- Menufacture Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('menufacture_id', 'Manufacture:') !!}
    {!! Form::select('menufacture_id', $menufactureItems, null, ['class' => 'form-control custom-select']) !!}
</div>

<!-- product warenty duration Field -->
<div class="form-group col-sm-6">
    {!! Form::label('warenty_duration', 'Warenty: (in month)') !!}
    {!! Form::text('warenty_duration', null, ['class' => 'form-control']) !!}
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
