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

    <h1 style="text-align: center">Riwayat Penjualan</h1>

<table id="customers">
    <thead>
        <tr>
            <th>No</th>
            <th></th>
            <th>Produk</th>
            <th>No nota</th>
            <th>Tgl pembelian</th>
            <th>Jml produk</th>
            <th>Total</th>
            <th>Penjual</th>
            <th>Pembeli</th>
            <th>Kurir</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=0;
        @endphp
        @foreach ($penjualan as $p)                       
            <tr>
                <td>{{++$i}}</td>
                <td>
                    <img class=" img-fluid" src="/foto_produk/{{$p->foto}}"alt="Card image cap" width="80" />
                </td>
                <td>{{$p->nama_produk}}</td>
                <td>{{$p->no_nota}}</td>
                <td>{{$p->tgl_pembelian}}</td>
                <td>{{$p->jml_produk}}</td>
                <td>{{$p->total}}</td>
                <td>{{$p->username}}</td>
                <td>{{$p->pembeli}}</td>
                <td>{{$p->kurir}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
