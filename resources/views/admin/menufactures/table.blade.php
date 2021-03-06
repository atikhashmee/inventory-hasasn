<div class="table-responsive">
    <table class="table data-table-lib" id="menufactures-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Description</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($menufactures as $menufacture)
            <tr>
                <td>{{ $menufacture->name }}</td>
            <td>{{ $menufacture->description }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.menufactures.destroy', $menufacture->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.menufactures.show', [$menufacture->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.menufactures.edit', [$menufacture->id]) }}"
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
