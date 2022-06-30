@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Top Selling Products</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Top Selling Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
           <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Product</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($best_selling_products) > 0)
                        @foreach ($best_selling_products as $key => $prod)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $prod->name }}</td>
                                <td>{{ $prod->totalCOunt }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table> 
            {{ $best_selling_products->links() }}
        </div>
    </section>
@endsection
