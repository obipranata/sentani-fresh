<?php

use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ProdukController@index');
Route::get('/produks', 'ProdukController@produk');
Route::post('/produks', 'ProdukController@produk');
Route::post('/produk/{kd_kategori}', 'ProdukController@pilihproduk');
Route::get('/single/{kd_produk}', 'ProdukController@single');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['verified']);

Route::middleware(['kurir'])->group(function(){
    Route::get('/notif', 'kurir\NotifController@notif');
    Route::post('/updatenotif/{kd_notif}', 'kurir\NotifController@updatenotif');
    Route::post('/kirimnotifbaru/{pembeli}/{kurir_reject}', 'kurir\NotifController@kirimnotifbaru');
    Route::post('/kirimpesan/{pembeli}', 'kurir\NotifController@kirimpesan');
    Route::post('/updatelokasi','kurir\KurirController@updatelokasi');
    Route::post('/updateplayerid','kurir\KurirController@updateplayerid');
});

Route::middleware(['admin'])->group(function(){
    Route::post('/admin/pembeli/download', 'admin\PembeliController@download');
    Route::post('/admin/kurir/download', 'admin\KurirController@download');
    Route::post('/admin/penjual/download', 'admin\PenjualController@download');
    Route::post('/admin/riwayatpenjualan/download', 'admin\PenjualanController@download');
    Route::post('/admin/pendapatan/download', 'admin\PendapatanController@download');
    Route::resource('kategori', 'admin\KategoriController');
    Route::resource('admin/pembeli', 'admin\PembeliController');
    Route::resource('admin/kurir', 'admin\KurirController');
    Route::resource('admin/penjual', 'admin\PenjualController');
    Route::resource('admin/penjualan', 'admin\PenjualanController');
    Route::resource('admin/pendapatan', 'admin\PendapatanController');
});

Route::middleware(['penjual'])->group(function(){
    Route::resource('produk', 'penjual\ProdukController');
    Route::delete('/detailproduk/{kd_detail_produk}', 'penjual\ProdukController@delete');
    Route::post('/tambahfoto/{kd_produk}','penjual\ProdukController@tambahfoto');
    Route::get('/riwayatpenjualan','penjual\RiwayatPenjualanController@index');
    Route::post('/topup_penjual', 'TopupController@index');
    Route::get('/notifpenjual', 'penjual\NotifController@index');
    Route::post('/updatenotifpenjual/{kd_notif}', 'Penjual\NotifController@updatenotif');
    Route::post('/updateplayeridpenjual','penjual\NotifController@updateplayerid');
    Route::post('/penjual/riwayatpenjualan/download', 'penjual\RiwayatPenjualanController@download');
});

Route::middleware(['pembeli'])->group(function(){
    Route::post('/tambahkeranjang/{kd_produk}', 'ProdukController@tambahkeranjang');
    Route::get('/keranjang', 'ProdukController@keranjang');
    Route::get('/pembelian', 'ProdukController@pembelian');
    Route::put('/updatekeranjang/{username}', 'ProdukController@updatekeranjang');
    Route::put('/editalamat', 'ProdukController@editalamat');
    Route::put('/bayarkurir/{kurir}/{total_ongkir}', 'ProdukController@bayarkurir');
    Route::post('/kirimnotif/{username}/{harga_produk}/{total_ongkir}', 'ProdukController@kirimnotif');
    Route::post('/keranjang/{kd_keranjang}', 'ProdukController@hapuskeranjang');
    Route::post('/nilaiproduk/{kd_pembelian}/{bintang}', 'ProdukController@nilaiproduk');
    Route::post('/insertnilai/{kd_pembelian}/{bintang}', 'ProdukController@insertnilai');
    Route::post('/topup', 'TopupController@index');
    Route::post('/updateplayeridpembeli','ProdukController@updateplayerid');
});

Route::post('/payment/notification', 'PaymentController@notification');
Route::get('/payment/completed', 'PaymentController@completed');
Route::get('/payment/failed', 'PaymentController@failed');
Route::get('/payment/unfinish', 'PaymentController@unfinish');

Route::get('/daftar-penjual', function(){
    return view('pengguna.daftar_penjual');
});
Route::get('/daftar-pembeli', function(){
    return view('pengguna.daftar_pembeli');
});
Route::get('/daftar-kurir', function(){
    return view('pengguna.daftar_kurir');
});
