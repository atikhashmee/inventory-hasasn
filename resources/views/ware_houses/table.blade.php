<div class="table-responsive">
    <table class="table" id="wareHouses-table">
        <thead>
        <tr>
            <th>Ware House Name</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($wareHouses as $wareHouse)
            <tr>
                <td>{{ $wareHouse->ware_house_name }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['wareHouses.destroy', $wareHouse->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('wareHouses.show', [$wareHouse->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('wareHouses.edit', [$wareHouse->id]) }}"
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
