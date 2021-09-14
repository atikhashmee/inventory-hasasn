<div class="table-responsive">
    <table class="table" id="challans-table">
        <thead>
        <tr>
            <th>Shop</th>
        <th>Customer</th>
        <th>Product Type</th>
        <th>Quantity</th>
        <th>Total&nbsp;Payable</th>
        <th>Challan Note</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($challans as $challan)
            <tr>
                <td>{{$challan->shop->name }}</td>
            <td>{{ $challan->customer->customer_name }}</td>
            <td>{{ $challan->product_type }}</td>
            <td>{{ $challan->quantity }} {{ $challan->unit->name }}</td>
            <td>{{ $challan->total_payable }}</td>
            <td>{{ $challan->challan_note }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.challans.destroy', $challan->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{url('print-challan-conditioned/'.$challan->id)}}"
                            class='btn btn-default btn-xs'>
                            <i class="fas fa-print"></i>
                        </a>
                        <a href="{{ route('admin.challans.show', [$challan->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.challans.edit', [$challan->id]) }}"
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
