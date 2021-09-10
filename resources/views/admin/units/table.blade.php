<div class="table-responsive">
    <table class="table" id="units-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Quantity Base</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($units as $unit)
            <tr>
                <td>{{ $unit->name }}</td>
            <td>{{ $unit->quantity_base }}</td>
            <td>{{ $unit->status }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.units.destroy', $unit->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.units.show', [$unit->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.units.edit', [$unit->id]) }}"
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
