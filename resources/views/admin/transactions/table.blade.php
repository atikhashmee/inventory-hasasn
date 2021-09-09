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
                    <td class="text-capitalize">{{$transaction->type}}</td>
                    <td>{{$transaction->amount}}</td>
                    <td>{{$transaction->created_at}}</td>
                    <td>
                        <form action="{{route('admin.transactions.destroy', ['transaction'=>$transaction])}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn sm" onclick="return confirm('Once you delete you won\'t be able to recover it.\nAre you still sure?')" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $transactions->links() }}