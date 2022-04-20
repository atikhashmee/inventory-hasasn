<table class="table table-bordered">
    <thead>
        <tr>
            <th>#SL</th>
            <th>Customer&nbsp;Name</th>
            <th>Transaction&nbsp;type</th>
            <th>Amount</th>
            <th>Date</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @if (count($transactions) > 0)
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{$transaction->id}}</td>
                    <td>{{$transaction->customer->customer_name}}</td>
                    <td class="text-capitalize">
                        
                        @if ($transaction->flag == 'order_placed')
                            <span>Sale</span> <br>
                           <small>(Product sale to customer)</small> 
                        @elseif($transaction->flag == 'refund')
                            <span>Money Return</span><br>
                            <small>(Refund Money to Customer)</small>
                        @elseif($transaction->flag == 'payment') 
                            <span>Payment</span>
                            <br>
                            <small>(Customer Payment)</small>
                        @elseif($transaction->flag == 'sell_return') 
                            <span>Sell Return</span><br>
                            <small>(Product Return)</small>
                        @endif
                        @if ($transaction->order_id)
                                <small style="border: 1px solid #d3d3d3; padding: 3px">
                                    <a href="{{route('admin.orders.show', ['order'=>$transaction->order_id])}}" target="_blank">{{$transaction->order_number}}</a>
                                </small>
                            @endif
                    </td>
                    <td>
                        {{ ($transaction->type == 'in' ? '+' : '-').$transaction->amount}}</td>
                    <td>{{$transaction->created_at}}</td>
                    <td>
                        <form action="{{route('admin.transactions.destroy', ['transaction'=>$transaction])}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Once you delete you won\'t be able to recover it.\nAre you still sure?')" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
        <tr>
            <td class="text-center" colspan="6">
                <span>No Items Found</span>
            </td>
        </tr>
        @endif
    </tbody>
</table>
{{ $transactions->links() }}