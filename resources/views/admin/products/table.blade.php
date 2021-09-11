<div class="table-responsive">
    <table class="table" id="products-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Origin</th>
                <th>Brand</th>
                <th>Menufacture</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($products as $key =>  $product)
            <tr>
                <td>{{ ++$key}}</td>
                <td><img src="{{asset($product->feature_image)}}" width="50" height="50" alt=""></td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->country_name ?? 'N/A' }}</td>
                <td>{{ $product->brand->name }}</td>
                <td>{{ $product->menufacture->name }}</td>
                <td>{{ $product->selling_price }}</td>
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
