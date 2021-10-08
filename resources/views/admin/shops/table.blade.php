<div class="table-responsive">
    <table class="table" id="shops-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Address</th>
        <th>Status</th>
        <th>Image</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($shops as $shop)
            <tr>
                <td class="text-capitalize">{{ $shop->name }}</td>
                <td style="  text-align: center;line-height: 20px;">
                    <p>
                        {!! $shop->address !!}
                    </p>
                </td>
                <td class="text-capitalize">{{ $shop->status }}</td>
                <td>
                    @if (file_exists(public_path().'/uploads/shops/'.$shop->image) && $shop->image)
                        <img src="{{asset('/uploads/shops/'.$shop->image)}}" class="rounded-circle" width="80" height="80" />
                    @else
                        <img src="{{asset('assets/img/not-found.png')}}" alt="" width="80" height="80" />
                    @endif
                </td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.shops.destroy', $shop->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('admin.shops.show', [$shop->id]) }}"
                            class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.shops.edit', [$shop->id]) }}"
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
