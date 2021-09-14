<div class="table-responsive">
    <table class="table" id="quotations-table">
        <thead>
            <tr>
                <th>Shop</th>
                <th>Recipient</th>
                <th>Recipient&nbsp;Address</th>
                <th>Date</th>
                <th>Subject</th>
                <th>Notes</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotations as $quotation)
                <tr>
                    <td>{{ $quotation->shop->name }}</td>
                <td>{{ $quotation->recipient }}</td>
                <td>{{ $quotation->recipient_address }}</td>
                <td>{{ $quotation->date }}</td>
                <td>{{ $quotation->subject }}</td>
                <td>{{ $quotation->notes }}</td>
                    <td width="120">
                        {!! Form::open(['route' => ['admin.quotations.destroy', $quotation->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            <a href="{{ route('admin.quotations.show', [$quotation->id]) }}"
                            class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.quotations.edit', [$quotation->id]) }}"
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
