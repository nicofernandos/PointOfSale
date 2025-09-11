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
    Route::get('/kamar', 'kamar');
    Route::get('/tambahkamar', 'tambahkamar');
    Route::post('/kamartambahsimpan', 'kamartambahsimpan');
    Route::get('/kamaredit/{id}', 'kamaredit');
    Route::put('/kamareditupdate/{id}', 'kamareditupdate');
    Route::delete('/kamarhapus/{id}', 'kamarhapus');

    //Layanan 
    Route::get('/layanan', 'layanan');
    Route::get('/tambahlayanan', 'tambahlayanan');
    Route::post('/tambahlayanansimpan', 'tambahlayanansimpan');
    Route::get('/tambahlayananedit/{id}', 'tambahlayananedit');
    Route::put('/tambahlayananeditsimpan/{id}', 'tambahlayananeditsimpan');
    Route::delete('/tambahlayananhapus/{id}', 'tambahlayananhapus');

    //Pelanggan
    Route::get('/tamu', 'tamu');
    Route::get('/tambahtamu', 'tambahtamu');
    Route::post('/tambahpelanggansimpan', 'tambahpelanggansimpan');
    Route::get('/tamuedit/{id}', 'tamuedit');
    Route::put('/pelangganeditsimpan/{id}', 'pelangganeditsimpan');
    Route::delete('/pelangganhapus/{id}', 'pelangganhapus');


    //Booking
    Route::get('/booking', 'booking');
    Route::get('/bookingtambah', 'bookingtambah');
    Route::post('/bookingtambahsimpan', 'bookingtambahsimpan');
    Route::get('/bookingedit/{id}', 'bookingedit');
    Route::put('/bookingeditsimpan/{id}', 'bookingeditsimpan');
    Route::delete('/bookinghapus/{id}', 'bookinghapus');
    Route::get('/bookingdetail/{id}', 'bookingdetail');


    //Laporan Tamu
    Route::get('/laporantamu', 'laporantamu');
    Route::get('/cetaklaporantamu', 'cetaklaporantamu');

    //Laporan Kunjungan
    Route::get('/laporankunjungan', 'laporankunjungan');
    Route::get('/cetaklaporankunjungan', 'cetaklaporankunjungan');

    // Pengguna
    Route::get('/penggunadaftar', 'penggunadaftar');
    Route::get('/penggunatambah', 'penggunatambah');
    Route::post('/penggunatambahsimpan', 'penggunatambahsimpan');
    Route::get('/penggunaedit/{id}', 'penggunaedit');
    Route::put('/penggunaeditsimpan/{id}', 'penggunaeditsimpan');
    Route::delete('/penggunahapus/{id}', 'penggunahapus');
});