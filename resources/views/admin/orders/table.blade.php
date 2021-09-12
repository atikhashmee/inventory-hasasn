<table class="table table-bordered">
    <thead>
        <tr>
            <th>Order&nbsp;ID</th>
            <th>Shop</th>
            <th>Customer&nbsp;Name</th>
            <th>Order&nbsp;Amount</th>
            <th>Order&nbsp;Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($orders) > 0)
            @foreach ($orders as $order)
                <tr>
                    <td>{{$order->order_number}}</td>
                    <td>{{$order->shop->name ?? 'N/A'}}</td>
                    <td>{{$order->customer->customer_name ?? 'N/A'}}</td>
                    <td>{{$order->total_amount}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-flat"><i class="fas fa-cogs"></i></button>
                            <button type="button" class="btn btn-default btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                <a class="dropdown-item" href="{{route('admin.orders.show', ['order'=>$order])}}">Detail</a>
                                <a class="dropdown-item" href="{{url('print-invoice/'.$order->id)}}" target="_blank">Print Invoice</a>
                                <a class="dropdown-item" href="{{url('print-challan/'.$order->id)}}" target="_blank">Print Challan</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
        <tr>
            <td class="text-center" colspan="5">
                <strong>No Item founds</strong>
            </td>
        </tr>
        @endif
    </tbody>
</table>
{{$orders->links()}}