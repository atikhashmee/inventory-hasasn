<div class="table-responsive">
    <table class="table" id="suppliers-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Website Url</th>
        <th>Contact Person Name</th>
        <th>Contact Email</th>
        <th>Contact Phone</th>
        <th>Country</th>
        <th>Address</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->website_url }}</td>
            <td>{{ $supplier->contact_person_name }}</td>
            <td>{{ $supplier->contact_email }}</td>
            <td>{{ $supplier->contact_phone }}</td>
            <td>{{ $supplier->country->name }}</td>
            <td>{{ $supplier->address }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.suppliers.destroy', $supplier->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.suppliers.show', [$supplier->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.suppliers.edit', [$supplier->id]) }}"
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
