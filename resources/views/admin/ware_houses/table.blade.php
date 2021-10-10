<div class="table-responsive">
    <table class="table data-table-lib" id="wareHouses-table">
        <thead>
        <tr>
            <th>Ware House Name</th>
            <th>Total Products</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($wareHouses as $wareHouse)
            <tr>
                <td>{{ $wareHouse->ware_house_name }}</td>
                <td>
                    <a href="{{ route('admin.products.index') }}?warehouse_id={{$wareHouse->id}}" class="btn-link">{{$wareHouse->total_products ?? 0}}</a>
                </td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.wareHouses.destroy', $wareHouse->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.wareHouses.show', [$wareHouse->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.wareHouses.edit', [$wareHouse->id]) }}"
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
