<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group @error('parent_id') has-error @enderror">
    <label class="required">{{ __('category.parent') }}*</label>
    <div class="">
        <?php $parent_id = (isset($data->parent_id)) ? $data->parent_id : old('parent_id'); ?>
        <select name="parent_id" class="form-control select2" required>
            <option value="0">Select a Parent</option>
            @foreach($categoryItems as $cat)
                <option value="{{ $cat->id }}" {{ ($parent_id==$cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                @if(!empty($cat->nested))
                    @foreach($cat->nested as $nc)
                        <option value="{{ $nc->id }}" {{ ($parent_id==$nc->id)?'selected':'' }}>&nbsp;&nbsp;-- {{ $nc->name }}</option>
                    @endforeach
                @endif
            @endforeach
        </select>
        @error('parent_id')
        <span class="help-block">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
