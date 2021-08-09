<div class="table-responsive">
    <table class="table" id="products-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Description</th>
        <th>Old Price</th>
        <th>Price</th>
        <th>Selling Price</th>
        <th>Quantity</th>
        <th>Slug</th>
        <th>Sku</th>
        <th>Category Id</th>
        <th>Brand Id</th>
        <th>Supplier Id</th>
        <th>Menufacture Id</th>
        <th>Warehouse Id</th>
        <th>Feature Image</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->old_price }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->selling_price }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->slug }}</td>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->category_id }}</td>
            <td>{{ $product->brand_id }}</td>
            <td>{{ $product->supplier_id }}</td>
            <td>{{ $product->menufacture_id }}</td>
            <td>{{ $product->warehouse_id }}</td>
            <td>{{ $product->feature_image }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.products.destroy', $product->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.products.show', [$product->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.products.edit', [$product->id]) }}"
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