@extends('layouts.templates_kurir')

@section('content_kurir')
<div class="page-heading">
    <h3>Kurir <span class="text-success"><small>{{Auth::user()->nama}}</small></span></h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Grafik Pengantaran</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="speedChart" width="600" height="400"></canvas>
                        </div>
                    </div>
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
        foreach ($pengantaran as $p) {
            echo "'".$p->bulan."',";
        }
    ?>],
    datasets: [{
        label: "Pengantaran per bulan",
        data: [<?php 
        foreach ($pengantaran as $p) {
            echo $p->total_pengantaran.",";
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