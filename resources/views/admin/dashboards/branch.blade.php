@extends('layouts.admin')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    @component('admin.partials.all_orders_widget', ['title' => 'Total Booked', 'icon' => 'fe-box', 'color' => 'warning'])
    {{$total_counts['booked'] ?? 0}}
    @endcomponent
    @component('admin.partials.all_orders_widget', ['title' => 'Total Assigned', 'icon' => 'fe-user', 'color' => 'danger'])
    {{$total_counts['assigned'] ?? 0}}
    @endcomponent
    @component('admin.partials.all_orders_widget', ['title' => 'Total Received', 'icon' => 'fe-layers', 'color' => 'info'])
    {{$total_counts['received'] ?? 0}}
    @endcomponent
    @component('admin.partials.all_orders_widget', ['title' => 'Total Shipped', 'icon' => 'fe-truck', 'color' => 'success'])
    {{$total_counts['shipped'] ?? 0}}
    @endcomponent
</div>
<!-- end row-->

<div class="row">
    <div class="col-12">
        <h3 class="font-weight-lighter border-bottom my-3 pb-2">Revenue</h3>
    </div>
    @component('admin.partials.sales_widget', ['title' => 'Sale', 'icon' => 'fe-box', 'color' => 'warning', 'total_amount' => $total_revenues['all_sales']])
    {{$todays_revenues['all_sales'] ?? 0}}
    @endcomponent
    @component('admin.partials.sales_widget', ['title' => 'Agent Expenses', 'icon' => 'fe-user', 'color' => 'danger', 'total_amount' => $total_revenues['agent_expenses']])
    {{$todays_revenues['agent_expenses'] ?? 0}}
    @endcomponent
    @component('admin.partials.sales_widget', ['title' => 'Expenses', 'icon' => 'fe-layers', 'color' => 'info', 'total_amount' => $total_revenues['expenses']])
    {{$todays_revenues['expenses'] ?? 0}}
    @endcomponent
    @component('admin.partials.sales_widget', ['title' => 'Profit', 'icon' => 'fe-truck', 'color' => 'success', 'total_amount' => $total_revenues['profit']])
    {{$todays_revenues['profit'] ?? 0}}
    @endcomponent
</div>
<!-- end row-->

<div class="row">
    <div class="col-sm-6">
        <div class="card-box">
            <h4 class="header-title mb-3">Total Orders Month Wise</h4>
            <div class="flot-chart chartjs-chart mt-4 pt-1">
                <canvas id="orders_chart" height="350"></canvas>
            </div>
        </div> <!-- end card-box -->
    </div> <!-- end col-->
    <div class="col-sm-6">
        <div class="card-box">
            <h4 class="header-title mb-3">Total Revenue Month Wise</h4>
            <div class="flot-chart chartjs-chart mt-4 pt-1">
                <canvas id="revenue_chart" height="350"></canvas>
            </div>
        </div> <!-- end card-box -->
    </div> <!-- end col-->
</div>
<!-- end row -->

@endsection

@section('page-scripts')
<!-- Plugins js-->
<script src="{{ asset('admin_assets') }}/libs/flatpickr/flatpickr.min.js"></script>
<script src="{{ asset('admin_assets') }}/libs/jquery-knob/jquery.knob.min.js"></script>
<script src="{{ asset('admin_assets') }}/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="{{ asset('admin_assets') }}/libs/chart-js/chart-js.min.js"></script>

<!-- Dashboar 1 init js-->
<script>
    if ($('#orders_chart').length > 0 && $('#revenue_chart').length > 0) {
        var _labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var revenue_data = {
            labels: _labels,
            datasets: [{
                label: "Total Revenue",
                backgroundColor: 'rgba(26, 128, 156, 0.3)',
                borderColor: '#1abc9c',
                data: JSON.parse('{{ $months_revenue }}')
            }]
        };
        var orders_data = {
            labels: _labels,
            datasets: [{
                label: "Total Orders",
                fill: true,
                // backgroundColor: 'transparent',
                borderColor: "#f1556c",
                // borderDash: [5, 5],
                data: JSON.parse('{{ $months_order_count }}')
            }]
        };
        var options = {
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                intersect: true
            },
            hover: {
                intersect: true
            },
            scales: {
                xAxes: [{
                    reverse: true,
                    gridLines: {
                        color: "rgba(0,0,0,0.05)"
                    }
                }],
                yAxes: [{
                    ticks: {
                        // stepSize: 100
                    },
                    display: true,
                    // borderDash: [5, 5],
                    gridLines: {
                        color: "rgba(0,0,0,0.05)",
                        fontColor: '#fff'
                    }
                }]
            }
        };
        var orders_selector = $("#orders_chart");
        var orders_ctx = orders_selector.get(0).getContext("2d");
        var orders_container = $("#line-chart-example").parent();
        var ww = orders_selector.attr('width', $(orders_container).width());
        var orders_chart, revenue_chart;

        orders_chart = new Chart(orders_ctx, {
            type: 'line',
            data: orders_data,
            options: options
        });

        var revenue_selector = $("#revenue_chart");
        var revenue_ctx = revenue_selector.get(0).getContext("2d");
        var revenue_container = $("#line-chart-example").parent();
        var ww = revenue_selector.attr('width', $(revenue_container).width());
        var revenue_chart;

        revenue_chart = new Chart(revenue_ctx, {
            type: 'line',
            data: revenue_data,
            options: options
        });
    } //barchart
</script>
@endsection