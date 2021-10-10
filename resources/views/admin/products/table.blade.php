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
                <td>
                    @if (file_exists(public_path().'/uploads/products/'.$product->feature_image) && $product->feature_image)
                        <img src="{{asset('/uploads/products/'.$product->feature_image)}}" class="rounded-circle" width="80" height="80" />
                    @else
                        <img src="{{asset('assets/img/not-found.png')}}" alt="" width="80" height="80" />
                    @endif
                </td>
                <td> <span class="p-1" style="border: 1px solid #d3d3d3; font-size:14px">{{ $product->code }}</span> {{ $product->name }}</td>
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
                        {{-- {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} --}}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
