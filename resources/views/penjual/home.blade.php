@extends('layouts.templates_penjual')

@section('content_penjual')
<div class="page-heading">
    <h3>Dashboard</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Grafik Penjualan</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="speedChart" width="600" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-5">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="admin/assets/images/faces/4.jpg" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold">{{Auth::user()->nama}}</h5>
                            <h6 class="text-muted mb-0">Penjual</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Top 3 Pelanggan</h4>
                </div>
                <div class="card-content pb-4">
                @php
                    $i=0;
                @endphp
                    @foreach ($pembeli as $p)       
                        @if ($i==3)
                            @php
                                break;
                            @endphp
                        @endif           
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="avatar avatar-lg">
                                <img src="admin/assets/images/faces/{{++$i}}.jpg">
                            </div>
                            <div class="name ms-4">
                                <h5 class="mb-1">{{$p->pembeli}}</h5>
                                <h6 class="text-muted mb-0">Rp{{number_format($p->total_pembelian)}}</h6>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/assets/js/chart.min.js"></script>
<script>
    var speedCanvas = document.getElementById("speedChart");

    // Chart.defaults.global.defaultFontFamily = "Lato";
    // Chart.defaults.global.defaultFontSize = 18;

var speedData = {
    labels: [<?php 
        foreach ($penjualan as $p) {
            echo "'".$p->bulan."',";
        }
    ?>],
    datasets: [{
        label: "Penjualan per bulan",
        data: [<?php 
        foreach ($penjualan as $p) {
            echo $p->total_produk.",";
        }
    ?>],
        lineTension: 0,
        fill: false,
        borderColor: 'orange',
        backgroundColor: 'transparent',
        borderDash: [5, 5],
        pointBorderColor: 'orange',
        pointBackgroundColor: 'rgba(255,150,0,0.5)',
        pointRadius: 5,
        pointHoverRadius: 10,
        pointHitRadius: 30,
        pointBorderWidth: 2,
        pointStyle: 'rectRounded'
    }]
};

var chartOptions = {
    legend: {
        display: true,
        position: 'top',
        labels: {
            boxWidth: 80,
            fontColor: 'black'
        }
    }
};

var lineChart = new Chart(speedCanvas, {
    type: 'line',
    data: speedData,
    options: chartOptions
});
</script>
@endsection