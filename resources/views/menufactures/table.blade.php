<div class="table-responsive">
    <table class="table" id="menufactures-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Description</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($menufactures as $menufacture)
            <tr>
                <td>{{ $menufacture->name }}</td>
            <td>{{ $menufacture->description }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['menufactures.destroy', $menufacture->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('menufactures.show', [$menufacture->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('menufactures.edit', [$menufacture->id]) }}"
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
