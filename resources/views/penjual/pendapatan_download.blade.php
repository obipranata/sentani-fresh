<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>
</head>
<body>

    <h1 style="text-align: center">Riwayat Pendapatan</h1>

<table id="customers">
    <thead>
        <tr>
            <th>No</th>
            <th>No Nota</th>
            <th>Tgl Pembelian</th>
            <th>Total Pendapatan (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=0;
        @endphp
        @foreach ($pendapatan as $p)                       
            <tr>
                <td>{{++$i}}</td>
                <td>{{$p->no_nota}}</td>
                <td>{{$p->tgl_pembelian}}</td>
                <td>{{number_format($p->total_pendapatan)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
