<div class="row">
    <div class="col-md-6">
        <!-- Shop Id Field -->
        <div class="form-group">
            {!! Form::label('shop_id', 'Shop:') !!}
            {!! Form::select('shop_id', $shopItems, null, ['class' => 'form-control select2 custom-select']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <!-- Date Field -->
        <div class="form-group">
            {!! Form::label('date', 'Date:') !!}
            {!! Form::date('date', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- Recipient Field -->
        <div class="form-group">
            {!! Form::label('recipient', 'Recipient:') !!}
            {!! Form::text('recipient', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <!-- Subject Field -->
        <div class="form-group">
            {!! Form::label('subject', 'Subject:') !!}
            {!! Form::text('subject', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<!-- Recipient Address Field -->
<div class="form-group">
    {!! Form::label('recipient_address', 'Recipient Address:') !!}
    {!! Form::textarea('recipient_address', null, ['class' => 'form-control', 'rows' => 0]) !!}
</div>

<div class="items-area mb-3">
    <div class="each-item rowclass" v-for="qitem in quotation_items" :data-id="qitem.item_id">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Select a Product</label>
                    <select name="product_id[]" id="product_id" class="form-control product_id_class_name select2">
                        <option value="">Product</option>
                        <option :value="pro.id" v-for="pro in products" >@{{pro.name}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Product Name</label>
                    <input type="text" class="form-control" name="product_names[]" v-model="qitem.product_name">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Brand Name</label>
                    <input type="text" class="form-control" name="brand_name[]" v-model="qitem.brand_name">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Model</label>
                    <input type="text" class="form-control" name="model[]" v-model="qitem.model">
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Origin</label>
                    <input type="text" class="form-control" name="origin[]" v-model="qitem.origin">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Quantity</label>
                    <input type="text" class="form-control" name="quantity[]"  v-model="qitem.quantity">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Unit Price</label>
                    <input type="text" class="form-control" name="unit_price[]" v-model="qitem.unit_price">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Total Price</label>
                    <input type="text" class="form-control" name="total_price[]" v-model="qitem.total_price">
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-primary float-right" type="button" @click="additem()">Add More</button>
</div>

<!-- Notes Field -->
<div class="form-group">
    {!! Form::label('notes', 'Notes:') !!}
    {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 0]) !!}
</div>