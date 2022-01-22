<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.home') }}" class="brand-link">
        @if (auth()->user()->role == 'admin')
        <img src="{{ asset('assets/img/meditech.png') }}"
             alt="{{ config('app.name') }} Logo"
             class="brand-image img-circle elevation-3">
        @else
            <img src="{{ $shop_logo }}"
                alt="{{ config('app.name') }} Logo"
                class="brand-image img-circle elevation-3">
        @endif
     
        
        @if (auth()->user()->role == 'admin')
            <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
        @else
            <span class="brand-text font-weight-light"> {{(auth()->user()->shop)?auth()->user()->shop->name:'N/A'}}</span>
        @endif
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>
</aside>
