<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['isLogin'])->controller(AdminController::class)->group(function () {
    // Profile
    Route::get('/profile', 'profile');
    Route::put('/profileupdate', 'profileupdate');

    Route::get('/dashboard', 'dashboard');

    //Kamar
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

    //Penjualan
    Route::get('/penjualan', 'penjualan');
    Route::get('/tambahpenjualan', 'tambahpenjualan');
    Route::post('/tambahpelanggansimpan', 'tambahpelanggansimpan');
    Route::get('/tamuedit/{id}', 'tamuedit');
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


    //Laporan Tamu
    Route::get('/laporantamu', 'laporantamu');
    Route::get('/cetaklaporantamu', 'cetaklaporantamu');

    //Laporan Pembelian
    Route::get('/laporanpembelian', 'laporanpembelian');
    Route::get('/cetaklaporankunjungan', 'cetaklaporankunjungan');
    
    //Laporan Pembelian
    Route::get('/laporanpenjualan', 'laporanpenjualan');
    // Pengguna
    Route::get('/penggunadaftar', 'penggunadaftar');
    Route::get('/penggunatambah', 'penggunatambah');
    Route::post('/penggunatambahsimpan', 'penggunatambahsimpan');
    Route::get('/penggunaedit/{id}', 'penggunaedit');
    Route::put('/penggunaeditsimpan/{id}', 'penggunaeditsimpan');
    Route::delete('/penggunahapus/{id}', 'penggunahapus');
});