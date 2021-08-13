<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Parent Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('parent_id', 'Parent Id:') !!}
    <select name="parent_id" id="parent_id" class="form-control custom-select">
        <option value="">---Parent---</option>
        @foreach ($categoryItems as $item)
            <option @if(isset($category) && $category->parent_id == $item['id']) selected @endif value="{{$item['id']}}">{{$item['name']}}</option>
        @endforeach
    </select>
</div>
