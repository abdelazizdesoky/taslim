@extends('Dashboard.layouts.master')
@section('css')
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    
@endsection
@section('title')
    الاذون | تسليماتى
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الاذون </h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كل
                    الاذون</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('Dashboard.messages_alert')
    <!-- row opened -->
    <div class="row row-sm">
        <!--div-->
        <div class="col-xl-6">
            <div class="card mg-b-20">
         
                <div class="card-body">

                
                    <h2>📊 عدد الفواتير حسب النوع</h2>
                    <canvas id="invoiceTypeChart"></canvas>
                <hr>
                    <h2>📊 عدد الفواتير حسب الحالة</h2>
                    <canvas id="invoiceStatusChart"></canvas>

                </div>
               
            </div>
            <!--/div-->
        </div>
    </div>
    </div>
    </div>
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('dashboard/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!--Internal Apexchart js-->
<script src="{{URL::asset('dashboard/js/apexcharts.js')}}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('/admin/invoice-chart-data')
            .then(response => response.json())
            .then(data => {
                console.log("✅ البيانات المسترجعة:", data);

                if (!data.invoiceTypes || !data.invoiceStatuses) {
                    console.error("🚨 لا توجد بيانات متاحة!");
                    return;
                }

                renderInvoiceTypeChart(data.invoiceTypes);
                renderInvoiceStatusChart(data.invoiceStatuses);
            })
            .catch(error => console.error("❌ خطأ أثناء جلب البيانات:", error));
    });

    function renderInvoiceTypeChart(invoiceTypes) {
        const typeLabels = { 1: "استلام", 2: "تسليم", 3: "مرتجعات" };

        const labels = invoiceTypes.map(item => typeLabels[item.invoice_type] || `نوع ${item.invoice_type}`);
        const counts = invoiceTypes.map(item => item.count);

        new Chart(document.getElementById('invoiceTypeChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'عدد الفواتير لكل نوع',
                    data: counts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    function renderInvoiceStatusChart(invoiceStatuses) {
        const statusLabels = { 1: "تحت استلام", 3: "مكتمل", 5: "ملغى" };

        const labels = invoiceStatuses.map(item => statusLabels[item.invoice_status] || `حالة ${item.invoice_status}`);
        const counts = invoiceStatuses.map(item => item.count);

        new Chart(document.getElementById('invoiceStatusChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'عدد الفواتير حسب الحالة',
                    data: counts,
                    backgroundColor: ['#4CAF50', '#36A2EB', '#FF6384'],
                    borderWidth: 1
                }]
            },
            options: { responsive: true }
        });
    }
</script>

@endsection


