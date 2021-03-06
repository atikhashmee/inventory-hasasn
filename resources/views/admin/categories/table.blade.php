<div class="table-responsive">
    <table class="table data-table-lib" id="categories-table">
        <thead>
        <tr>
            <th>SL</th>
            <th>Name</th>
            <th>Total Products</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @php
            $counter = 0;
        @endphp
        @foreach($categories as $category)
            <tr>
                <td>{{ ++$counter }}</td>
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
            @if (count($category->nested) > 0)
                @foreach ($category->nested as $item)
                    <tr>
                        <td>{{ ++$counter }}</td>
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
                    @if (count($item->nested) > 0)
                        @foreach ($item->nested as $childItem)
                            <tr>
                                <td>{{ ++$counter }}</td>
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
