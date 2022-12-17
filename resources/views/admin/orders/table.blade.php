<table class="table table-bordered">
    <thead>
        <tr>
            <th>SL</th>
            <th>Order&nbsp;Number</th>
            <th>Shop</th>
            <th>Customer&nbsp;Name</th>
            <th>Order&nbsp;Amount</th>
            <th>Order&nbsp;Date</th>
            <th>Payment&nbsp;Status</th>
            <th>Order&nbsp;Status</th>
            <th>Sold&nbsp;By</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($orders) > 0)
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $serial-- }}</td>
                    <td>{{$order->order_number}}</td>
                    <td>{{$order->shop->name ?? 'N/A'}}</td>
                    <td>{{$order->customer->customer_name ?? 'N/A'}}</td>
                    <td>{{$order->total_amount}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>
                        @if ($order->order_total_payemnt == $order->total_final_amount)
                            Paid
                        @else    
                            @if ($order->order_total_payemnt == 0)
                               Not Paid 
                            @else
                                Partial
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($order->status == "Drafted")
                            Drafted
                        @else    
                            Sold
                        @endif
                    </td>
                    <td>{{$order->user? $order->user->name : 'N/A'}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-flat"><i class="fas fa-cogs"></i></button>
                            <button type="button" class="btn btn-default btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                @if ($order->status == "Drafted")
                                    <a class="dropdown-item" href="{{ route('admin.orders.create') }}?order_id={{$order->id}}">Edit & Sale</a>
                                @endif
                                <a class="dropdown-item" href="{{route('admin.orders.show', ['order'=>$order])}}">Detail</a>
                                <a class="dropdown-item" href="{{url('print-invoice/'.$order->id)}}" target="_blank">Print Invoice</a>
                                <a class="dropdown-item" href="{{url('print-challan/'.$order->id)}}" target="_blank">Print Challan</a>
                                @if (intval($order->wr_order_details) > 0)
                                    <a class="dropdown-item" href="{{url('print-warenty-serials/'.$order->id)}}" target="_blank"></i>Print Warranty Serial</a>
                                @endif
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