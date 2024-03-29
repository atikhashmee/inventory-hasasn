@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $total_sales_today }}</h3>
                            <p>Today Sales</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $total_refunds_today }}</h3>
                            <p>Today Refunds</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
				@if (auth()->user()->role == 'admin')
					<div class="col-lg-3 col-6">
						<div class="small-box bg-success">
							<div class="inner">
								<h3>{{ $total_purchase_today }}</h3>
								<p>Today Purchase</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							{{-- <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
						</div>
					</div>
					<div class="col-lg-3 col-6">
						<div class="small-box bg-warning">
							<div class="inner">
								<h3>{{ $total_payment_today }}</h3>
								<p>Today Payments</p>
							</div>
							<div class="icon">
								<i class="ion ion-person-add"></i>
							</div>
							{{-- <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
						</div>
					</div>
					<div class="col-lg-3 col-6">
						<div class="small-box bg-danger">
							<div class="inner">
								<h3>{{ $total_due_today }}</h3>
								<p>Today Due</p>
							</div>
							<div class="icon">
								<i class="ion ion-pie-graph"></i>
							</div>
							{{-- <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
						</div>
					</div>

					<div class="col-lg-3 col-6">
						<div class="small-box" style="background: #6f42c1; color: #fff">
							<div class="inner">
								<h3>{{ $total_regular_sales }}</h3>
								<p>Regular sales</p>
							</div>
							<div class="icon">
								<i class="ion ion-pie-graph"></i>
							</div>
							{{-- <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
						</div>
					</div>

					<div class="col-lg-3 col-6">
						<div class="small-box" style="background: #007bff; color: #fff">
							<div class="inner">
								<h3>{{ $total_condition_sales }}</h3>
								<p>Condition sales</p>
							</div>
							<div class="icon">
								<i class="ion ion-pie-graph"></i>
							</div>
							{{-- <a href="javascript:void(0)" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
						</div>
					</div>

				@endif
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable ui-sortable">
                    <!-- TO DO List -->
                    <div class="card">
                        <div class="card-header ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                Recent 5 sales
                            </h3>

                            <div class="card-tools">

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($recent_sales) > 0)
                                        @foreach ($recent_sales as $key => $sale)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $sale->customer->customer_name ?? 'N/A' }}</td>
                                                <td>{{ $sale->total_final_amount }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                Recent 5 Purchase
                            </h3>

                            <div class="card-tools">

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Supplier</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($recent_purchase) > 0)
                                        @foreach ($recent_purchase as $key => $pur)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $pur->supplier->name ?? 'N/A' }}</td>
                                                <td>{{ $pur->product->name ?? 'N/A' }}</td>
                                                <td>{{ $pur->quantity }}</td>
                                                <td>{{ $pur->price }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-5 connectedSortable ui-sortable">

                    <!-- solid sales graph -->
                    <div class="card">
                        <div class="card-header ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                Top 5 Selling Products
                            </h3>

                            <div class="card-tools">
                                <a href="{{ route("admin.topSellingProducts") }}">See All</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
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
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header ui-sortable-handle" style="cursor: move;">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                Top 5 Customers Outstandings
                            </h3>

                            <div class="card-tools">
                                <a href="{{ route("admin.outstandingCustomers") }}">See All</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>        
                                        <th>Customer ID</th>
                                        <th>Customer Name</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($outstanding_dues) > 0)
                                        @foreach ($outstanding_dues as $key => $customer)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $customer->id }}</td>
                                                <td>{{ $customer->customer_name }}</td>
                                                <td>{{ number_format($customer->total_due, 2, ",", ".") }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
