<div class="table-responsive">
    <table class="table data-table-lib" id="shops-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Address</th>
        <th>Status</th>
        <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($shops as $shop)
            <tr>
                <td>{{ $shop->name }}</td>
            <td>{{ $shop->address }}</td>
            <td>{{ $shop->status }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.shops.destroy', $shop->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.shops.show', [$shop->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.shops.edit', [$shop->id]) }}"
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
