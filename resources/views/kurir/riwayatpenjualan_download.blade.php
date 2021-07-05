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

    <h1 style="text-align: center">Riwayat Pengantaran</h1>

<table id="customers">
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl Pembelian</th>
            <th>Nama Pembeli</th>
            <th>Jml Produk</th>
            <th>Alamat Pembeli</th>
            <th>Total Ongkir</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=0;
        @endphp
                        @foreach ($penjualan as $p)                       
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$p->tgl_pembelian}}</td>
                            <td>{{$p->pembeli}}</td>
                            <td>{{$p->jml}}</td>
                            <td>{{$p->alamat_pembeli}}</td>
                            <td>{{round($p->total_ongkir)}}</td>
                        </tr>
                    @endforeach
    </tbody>
</table>

</body>
</html>
