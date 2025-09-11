<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookingdetail extends Model
{
    protected $table = 'bookingdetail';
    protected $primaryKey = 'idbookingdetail';
    public $timestamps = false;

    protected $fillable = [
        'idbooking',
        'idlayanantambahan',
        'jumlah',
        'harga',
        'subtotal',
    ];

    // Relasi ke Booking (detail milik satu booking)
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'idbooking', 'idbooking');
    }

    // Relasi ke LayananTambahan (detail mengacu ke satu layanan tambahan)
    public function layananTambahan()
    {
        return $this->belongsTo(Layanantambahan::class, 'idlayanantambahan', 'idlayanantambahan');
    }
}