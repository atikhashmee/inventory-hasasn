<!-- Product Id Field -->
@if (Route::is('admin.stocks.create'))
    <div class="form-group col-sm-6">
        <label for="product_id"> Product:</label>
        <select name="product_id" id="product_id" class="form-control custom-select select2">
            <option value="">Select a product</option>
            @foreach ($productItems as $p_id => $item)
                <option value="{{$p_id}}" @if(isset($stock) && $stock->id == $p_id) selected @endif>{{$item}}</option>
            @endforeach
        </select>
    </div>
@endif


<!-- Supplier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('supplier_id', 'Supplier:') !!}
    {!! Form::select('supplier_id', $supplierItems, null, ['class' => 'form-control custom-select select2']) !!}
</div>


<!-- Warehouse Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('warehouse_id', 'Warehouse:') !!}
    {!! Form::select('warehouse_id', $ware_houseItems, null, ['class' => 'form-control custom-select select2']) !!}
</div>


<!-- Sku Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sku', 'Sku:') !!}
    {!! Form::text('sku', null, ['class' => 'form-control']) !!}
</div>

<!-- Old Price Field -->
@if (Route::is('admin.stocks.create')) 
    <div class="form-group col-sm-6">
        <label for="old_price">Old Price:</label>
        <input type="text" name="old_price" id="old_price" class="form-control" readonly :value="product?product.price:null">
    </div>
@endif

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