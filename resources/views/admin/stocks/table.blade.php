<div class="table-responsive">
    <table class="table" id="stocks-table">
        <thead>
        <tr>
            <th>Product Id</th>
        <th>Warehouse Id</th>
        <th>Sku</th>
        <th>Old Price</th>
        <th>Price</th>
        <th>Selling Price</th>
        <th>Quantity</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stocks as $stock)
            <tr>
                <td>{{ $stock->product->name }}</td>
            <td>{{ $stock->warehouse->ware_house_name }}</td>
            <td>{{ $stock->sku }}</td>
            <td>{{ $stock->old_price }}</td>
            <td>{{ $stock->price }}</td>
            <td>{{ $stock->selling_price }}</td>
            <td>{{ $stock->quantity }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.stocks.destroy', $stock->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.stocks.show', [$stock->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.stocks.edit', [$stock->id]) }}"
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
