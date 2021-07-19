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

<div id="googleMap" class="bg-white" style="height: 0;"></div>

<script src="/assets/js/jquery.min.js"></script>
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

<script type="text/javascript">
    function myMap() {
        var mapProp = {
            center: new google.maps.LatLng(-2.53371, 140.71813),
            zoom: 13,
        };
        var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
        var marker;

        var startPos;
            var geoSuccess = function(position) {
                startPos = position;
                console.log(startPos.coords.latitude);
                console.log(startPos.coords.longitude);
                let url = "{{ url('/updatelokasi') }} ";

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        'lat' : startPos.coords.latitude,
                        'lng' : startPos.coords.longitude,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data){
                        console.log(data);
                    }
                });
            };
            navigator.geolocation.getCurrentPosition(geoSuccess);
  
        var infowindow = new google.maps.InfoWindow({});
  
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLsBUFqgsrYYjB_jXFkC1Esh8qQv-Yzw4&callback=myMap"></script>

@endsection