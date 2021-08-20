<div class="table-responsive">
    <table class="table" id="categories-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Total Products</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->total_product??0 }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.categories.destroy', $category->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.categories.show', [$category->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.categories.edit', [$category->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
            @if ($category->nested()->count() > 0)
                @foreach ($category->nested()->get() as $item)
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;-{{ $item->name }}</td>
                        <td>{{ $item->total_product??0 }}</td>
                        <td width="120">
                            {!! Form::open(['route' => ['admin.categories.destroy', $item->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{{ route('admin.categories.show', [$item->id]) }}"
                                class='btn btn-default btn-xs'>
                                    <i class="far fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', [$item->id]) }}"
                                class='btn btn-default btn-xs'>
                                    <i class="far fa-edit"></i>
                                </a>
                                {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @if ($item->nested()->count() > 0)
                        @foreach ($item->nested()->get() as $childItem)
                            <tr>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--{{ $childItem->name }}</td>
                                <td>{{ $childItem->total_product??0 }}</td>
                                <td width="120">
                                    {!! Form::open(['route' => ['admin.categories.destroy', $childItem->id], 'method' => 'delete']) !!}
                                    <div class='btn-group'>
                                        <a href="{{ route('admin.categories.show', [$childItem->id]) }}"
                                        class='btn btn-default btn-xs'>
                                            <i class="far fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', [$childItem->id]) }}"
                                        class='btn btn-default btn-xs'>
                                            <i class="far fa-edit"></i>
                                        </a>
                                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                
            @endif
        @endforeach
        </tbody>
    </table>
</div>
