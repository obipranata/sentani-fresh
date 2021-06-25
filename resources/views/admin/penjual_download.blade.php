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

    <h1 style="text-align: center">Data Penjual</h1>

<table id="customers">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Toko</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Username</th>
            <th>Tgl Daftar</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=0;
        @endphp
        @foreach ($penjual as $p)   
            <tr>
                <td>{{++$i}}</td>
                <td>{{$p->nama}}</td>
                <td>{{$p->nama_toko}}</td>
                <td>{{$p->alamat}}</td>
                <td>{{$p->no_hp}}</td>
                <td>{{$p->username}}</td>
                <td>{{$p->created_at}}</td>
            </tr>
            
        @endforeach
    </tbody>
</table>

</body>
</html>
