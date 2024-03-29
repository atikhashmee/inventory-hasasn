@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @component('admin.reports.nav')@endcomponent
                </div>
                <div class="col-sm-6">
                    <form action="{{route('admin.report.profitloss')}}">
                        <div class="d-flex flex-row-reverse">
                            <button class="btn btn-default" type="submit"><i class="fa fa-filter">Filter</i></button>
                            @php
                                $selectedYear = date('Y');
                                if (Request::get('year')) {
                                    $selectedYear = Request::get('year');
                                }
                            @endphp
                            <select name="year" class="form-control select2">
                                @foreach (range(2021, 2030) as $year )
                                    <option @if($selectedYear == $year ) selected @endif>{{$year}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive h-70">
                    <table class="table tbl-border table-hover table-2nd-no-sort" id="file_export">
                        <thead>
                        <tr>
                            <th>Date/Month</th>
                            <th>January</th>
                            <th>February</th>
                            <th>March</th>
                            <th>April</th>
                            <th>May</th>
                            <th>June</th>
                            <th>July</th>
                            <th>August</th>
                            <th>September</th>
                            <th>October</th>
                            <th>November</th>
                            <th>December</th>
                        </tr>
                        </thead>
                        <tbody>
                        @for ($i = 1; $i < 32; $i++)
                            <tr>
                                <td>{{ $i }}</td>
                                @for ($j = 1; $j < 13; $j++)
                                    <td>
                                        @if (isset($data[$j][$i]))
                                            <a  href="{{route('admin.report.sells_detail')}}?date={{$selectedYear}}-{{$j}}-{{$i}}">{{$data[$j][$i]}}</a>
                                        @else
                                            --
                                        @endif
                                    </td>
                                    @php
                                        $monthData[$j][] = isset($data[$j][$i]) ? $data[$j][$i] : 0;
                                    @endphp
                                @endfor
                            </tr>
                        @endfor
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Total</th>
                            @foreach ($monthData as $mon)
                                <th>{{ array_sum($mon) }}</th>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection



