<a class="btn btn-default @if(url()->current() == route('admin.report.sells')) active @endif" href="{{route('admin.report.sells')}}">Sells</a>
<a class="btn btn-default @if(url()->current() == route('admin.report.purchase')) active @endif" href="{{route('admin.report.purchase')}}">Purchase</a>
<a class="btn btn-default @if(url()->current() == route('admin.report.payment')) active @endif" href="{{route('admin.report.payment')}}">Payment</a>
<a class="btn btn-default @if(url()->current() == route('admin.report.profitloss')) active @endif" href="{{route('admin.report.profitloss')}}">Profit & loss</a>

    


