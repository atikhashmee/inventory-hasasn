<div class="table-responsive">
    <table class="table data-table-lib" id="products-table">
        <thead>
            <tr>
                <th>Feature Image</th>
                <th>Name</th>
                <th>Sku</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Supplier</th>
                <th>Menufacture</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>
                        <img src="{{Storage::path('products/'.$product->feature_image)}}" alt="">
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ ($product->category)? $product->category->name : 'N/A' }}</td>
                    <td>{{ ($product->brand)? $product->brand->name: 'N/A' }}</td>
                    <td>{{ ($product->supplier)?$product->supplier->name: 'N/A' }}</td>
                    <td>{{ ($product->menufacture)? $product->menufacture->name: 'N/A' }}</td>
                    <td>{{ $product->price }}</td>
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
