<div class="table-responsive">
    <table class="table data-table-lib" id="stocks-table">
        <thead>
        <tr>
            <th>Product</th>
        <th>Supplier</th>
        <th>Warehouse</th>
        <th>Sku</th>
        <th>Old Price</th>
        <th>Price</th>
        <th>Selling Price</th>
        <th>Quantity</th>
            <th >Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stocks as $stock)
            <tr>
                <td>{{ ($stock->product)?$stock->product->name:'N/A' }}</td>
            <td>{{ ($stock->supplier)?$stock->supplier->name:'N/A' }}</td>
            <td>{{ ($stock->warehouse)?$stock->warehouse->ware_house_name:'N/A' }}</td>
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
