<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['isLogin'])->controller(AdminController::class)->group(function () {
    // Profile
    Route::get('/profile', 'profile');
    Route::put('/profileupdate', 'profileupdate');

    Route::get('/dashboard', 'dashboard');

    //Barang
    Route::get('/barang', 'barang');
    Route::get('/tambahbarang', 'tambahbarang');
    Route::post('/barangtambahsimpan', 'barangtambahsimpan');
    Route::get('/barangedit/{id}', 'barangedit');
    Route::put('/barangeditupdate/{id}', 'barangeditupdate');
    Route::delete('/baranghapus/{id}', 'baranghapus');

    //Stok 
    Route::get('/stok', 'stok');
    Route::get('/tambahstok', 'tambahstok');
    Route::post('/tambahstoksimpan', 'tambahstoksimpan');
    Route::get('/tambahstokedit/{id}', 'tambahstokedit');
    Route::put('/tambahstokeditsimpan/{id}', 'tambahstokeditsimpan');
    Route::delete('/stokhapus/{id}', 'stokhapus');

    //Kategori
    Route::get('/kategori','kategori');
    Route::get('/tambahkategori','tambahkategori');
    Route::post('/tambahkategorisimpan','tambahkategorisimpan');
    Route::get('/kategoriedit/{id}','kategoriedit');
    Route::put('/kategorieditsimpan/{id}','kategorieditsimpan');
    Route::delete('/kategorihapus/{id}','kategorihapus');

    //Pelanggan
    Route::get('/pelanggan', 'pelanggan');
    Route::get('/tambahpelanggan', 'tambahpelanggan');
    Route::post('/tambahpelanggansimpan', 'tambahpelanggansimpan');
    Route::get('/pelangganedit/{id}', 'pelangganedit');
    Route::put('/pelangganeditsimpan/{id}', 'pelangganeditsimpan');
    Route::delete('/pelangganhapus/{id}', 'pelangganhapus');


    //Pembelian
    Route::get('/pembelian', 'pembelian');
    Route::get('/pembeliantambah', 'pembeliantambah');
    Route::post('/pembeliantambahsimpan', 'pembeliantambahsimpan');
    Route::get('/pembelianedit/{id}', 'pembelianedit');
    Route::put('/pembelianeditsimpan/{id}', 'pembelianeditsimpan');
    Route::delete('/pembelianhapus/{id}', 'pembelianhapus');
    Route::get('/pembeliandetail/{id}', 'pembeliandetail');


    //Penjualan
    Route::get('/penjualan','penjualan');
    Route::get('/penjualantambah','penjualantambah');
    Route::get('penjualantambahsimpan','penjualantambahsimpan');
    Route::get('/penjualanedit/{id}','penjualanedit');
    Route::get('/penjualaneditsimpan/{id}','penjualaneditsimpan');
    Route::get('/penjualanhapus','penjualanhapus');
    Route::get('/pembeliandetail','pembeliandetail');


    //Laporan Tamu
    Route::get('/laporantamu', 'laporantamu');
    Route::get('/cetaklaporantamu', 'cetaklaporantamu');

    //Laporan Pembelian
    Route::get('/laporanpembelian', 'laporanpembelian');
    Route::get('/cetaklaporankunjungan', 'cetaklaporankunjungan');
    
    //Laporan Pembelian
    Route::get('/laporanpenjualan', 'laporanpenjualan');
});