<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';

    protected $primaryKey = 'idkamar';

    public $timestamps = false;

    protected $fillable = [
        'namakamar',
        'harga',
        'deskripsi',
    ];

    public function fotos()
    {
        return $this->hasMany(Kamarfoto::class, 'idkamar', 'idkamar');
}

}