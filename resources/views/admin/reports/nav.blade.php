<a class="@if(url()->current() == route('admin.report.sells')) btn btn-primary @else btn btn-default @endif" href="{{route('admin.report.sells')}}">Sells</a>
<a class="@if(url()->current() == route('admin.report.purchase')) btn btn-primary @else btn btn-default @endif" href="{{route('admin.report.purchase')}}">Purchase</a>
<a class="@if(url()->current() == route('admin.report.payment')) btn btn-primary @else btn btn-default @endif" href="{{route('admin.report.payment')}}">Payment</a>
<a class="@if(url()->current() == route('admin.report.profitloss')) btn btn-primary @else btn btn-default @endif" href="{{route('admin.report.profitloss')}}">Profit & loss</a>

    


