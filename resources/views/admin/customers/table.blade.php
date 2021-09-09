<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer&nbsp;Name</th>
            <th>Customer&nbsp;Phone</th>
            <th>Customer&nbsp;E-mail</th>
            <th>Customer&nbsp;Addresss</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($customers) > 0)
            @foreach ($customers as $customer)
                <tr>
                    <td>{{$customer->id}}</td>
                    <td>{{$customer->customer_name}}</td>
                    <td>{{$customer->customer_phone}}</td>
                    <td>{{$customer->customer_email}}</td>
                    <td>{{$customer->customer_address}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-flat"><i class="fas fa-cogs"></i></button>
                            <button type="button" class="btn btn-default btn-flat dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                <a class="dropdown-item" href="{{route('admin.customers.edit', ['customer'=>$customer])}}">Edit</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="document.getElementById('delete_form').submit()">Delete</a>
                                <form action="{{route('admin.customers.destroy', ['customer'=>$customer])}}" id="delete_form" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div> 
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{$customers->links()}}