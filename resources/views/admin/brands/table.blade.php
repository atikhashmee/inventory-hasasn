<div class="table-responsive">
    <table class="table data-table-lib" id="brands-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($brands as $brand)
            <tr>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->description }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.brands.destroy', $brand->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.brands.show', [$brand->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.brands.edit', [$brand->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
