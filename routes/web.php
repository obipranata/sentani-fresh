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
Route::post('/produk/{kd_kategori}', 'ProdukController@pilihproduk');
Route::get('/single/{kd_produk}', 'ProdukController@single');



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['verified']);

Route::middleware(['kurir'])->group(function(){
    Route::get('/notif', 'kurir\NotifController@notif');
    Route::post('/updatenotif/{kd_notif}', 'kurir\NotifController@updatenotif');
    Route::post('/kirimnotifbaru/{pembeli}/{kurir_reject}', 'kurir\NotifController@kirimnotifbaru');
    Route::post('/updatelokasi','kurir\KurirController@updatelokasi');
});

Route::middleware(['admin'])->group(function(){
    // // tampil data
    // Route::get('/kategori', 'admin\KategoriController@index');

    // // halaman insert data
    // Route::get('/kategori/create', 'admin\KategoriController@create');

    // // halaman edit data
    // Route::get('/kategori/{kd_kategori}/edit', 'admin\KategoriController@edit');

    // // simpan data kedalam database
    // Route::post('/kategori', 'admin\KategoriController@store');

    // // ubah data kedalam data base
    // Route::put('/kategori/{kd_kategori}', 'admin\KategoriController@update');

    // // hapus data
    // Route::delete('/kategori/{kd_kategori}', 'admin\KategoriController@destroy');

    Route::resource('kategori', 'admin\KategoriController');
});

Route::middleware(['penjual'])->group(function(){
    Route::resource('produk', 'penjual\ProdukController');
    Route::delete('/detailproduk/{kd_detail_produk}', 'penjual\ProdukController@delete');
    Route::post('/tambahfoto/{kd_produk}','penjual\ProdukController@tambahfoto');
    Route::get('/riwayatpenjualan','penjual\RiwayatPenjualanController@index');
    Route::post('/topup_penjual', 'TopupController@index');
});

Route::middleware(['pembeli'])->group(function(){
    Route::post('/tambahkeranjang/{kd_produk}', 'ProdukController@tambahkeranjang');
    Route::get('/keranjang', 'ProdukController@keranjang');
    Route::get('/pembelian', 'ProdukController@pembelian');
    Route::put('/updatekeranjang/{username}', 'ProdukController@updatekeranjang');
    Route::put('/editalamat', 'ProdukController@editalamat');
    Route::put('/bayarkurir/{kurir}/{total_ongkir}', 'ProdukController@bayarkurir');
    Route::post('/kirimnotif/{username}/{harga_produk}/{total_ongkir}', 'ProdukController@kirimnotif');
    Route::delete('/keranjang/{kd_keranjang}', 'ProdukController@hapuskeranjang');
    Route::post('/nilaiproduk/{kd_pembelian}/{bintang}', 'ProdukController@nilaiproduk');
    Route::post('/insertnilai/{kd_pembelian}/{bintang}', 'ProdukController@insertnilai');
    Route::post('/topup', 'TopupController@index');
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
