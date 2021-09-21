<div class="d-flex flex-row-reverse">
    <span>{{date('M d, Y', strtotime($quotation->date))}}</span>
</div>

<div class="d-flex flex-column">
    <strong>To</strong>
    <strong>{{ $quotation->recipient }}</strong>
    <address>
        {{ $quotation->recipient_address }}
    </address>
</div>

<div class="d-flex">
    <span>Sub</span>: &nbsp;
    <strong><u>{{ $quotation->subject }} </u></strong>
</div>
<br>
<br>
<table width="100%" border="1" style="text-align: center">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Description of Items</th>
            <th>Origin</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
        @if (count($quotation->items) > 0)
            @foreach ($quotation->items as $k => $item)
                <tr>
                    <td>{{++$k}}</td>
                    <td>{{$item->item_name}}</td>
                    <td>{{$item->origin}}</td>
                    <td>{{$item->quantity}}</td>
                    <td>{{$item->unit_price}}</td>
                    <td>{{$item->total_price}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">No item to show</td>
            </tr>
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center">
    <strong>two lacs two hundarad</strong>
</div>
<br>
<br>
<div class="d-flex">
    <p>{{ $quotation->notes }}</p>
</div>



