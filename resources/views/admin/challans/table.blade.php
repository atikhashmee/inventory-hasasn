<div class="table-responsive">
    <table class="table" id="challans-table">
        <thead>
        <tr>
            <th>SL</th>
            <th>Shop</th>
        <th>Customer</th>
        <th>Product Type</th>
        <th>Quantity</th>
        <th>Total&nbsp;Payable</th>
        <th>Challan Note</th>
        <th>Status</th>
        <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($challans as $challan)
            <tr>
                <td>{{ $serial-- }}</td>
                <td>{{$challan->shop->name }}</td>
            <td>{{ isset($challan->customer)? $challan->customer->customer_name: "N/A" }} </td>
            <td>{{ $challan->product_type }}</td>
            <td>{{ $challan->quantity }} {{ isset($challan->unit)? $challan->unit->name: "N/A" }} </td>
            <td>{{ $challan->total_payable ?? 'N/A' }}</td>
            <td>{{ $challan->challan_note }}</td>
            <td>{{ $challan->status }}
                <a href="javascript:void(0)" onclick="document.querySelector('#update_id_{{$challan->id}}').submit()"><small>Change Status</small></a>
                {!! Form::model($challan, ['route' => ['admin.challans.update', $challan->id], 'method' => 'patch', 'id' => 'update_id_'.$challan->id]) !!}
                    <input type="hidden" name="status" value="{{ $challan->status == "Collected" ? "Not Collected" : 'Collected' }}">
                    {{-- these two field is used to fullfill the minimal requirement of the update function --}}
                    <input type="hidden" name="product_type" value="{{ $challan->product_type }}">
                    <input type="hidden" name="quantity" value="{{ $challan->quantity }}">
                    <input type="hidden" name="challan_type" value="{{ $challan->challan_type }}">
                    <input type="hidden" name="total_payable" value="{{ $challan->total_payable }}">
                {!! Form::close() !!}
            </td>
                <td width="120">
                    {!! Form::open(['route' => ['admin.challans.destroy', $challan->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{url('print-challan-conditioned/'.$challan->id)}}"
                            target="_blank"
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
{{ $challans->links() }}
