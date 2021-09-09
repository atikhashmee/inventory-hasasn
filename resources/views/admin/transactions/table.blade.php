<table class="table table-bordered">
    <thead>
        <tr>
            <th>#SL</th>
            <th>Customer&nbsp;Name</th>
            <th>Transaction&nbsp;type</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @if (count($transactions) > 0)
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{$transaction->id}}</td>
                    <td>{{$transaction->customer->customer_name}}</td>
                    <td>{{$transaction->type}}</td>
                    <td>{{$transaction->amount}}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $transactions->links() }}