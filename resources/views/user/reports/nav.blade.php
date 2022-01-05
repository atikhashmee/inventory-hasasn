<a class="@if(url()->current() == route('user.report.sells')) btn btn-primary @else btn btn-default @endif" href="{{route('user.report.sells')}}">Sells</a>
<a class="@if(url()->current() == route('user.report.purchase')) btn btn-primary @else btn btn-default @endif" href="{{route('user.report.purchase')}}">Purchase</a>
<a class="@if(url()->current() == route('user.report.payment')) btn btn-primary @else btn btn-default @endif" href="{{route('user.report.payment')}}">Payment</a>
<a class="@if(url()->current() == route('user.report.profitloss')) btn btn-primary @else btn btn-default @endif" href="{{route('user.report.profitloss')}}">Profit & loss</a>

    


