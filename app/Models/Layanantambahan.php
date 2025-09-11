<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanantambahan extends Model
{
    protected $table = 'layanantambahan';

    protected $primaryKey = 'idlayanantambahan';

    public $timestamps = false;

    protected $fillable = [
        'namalayanantambahan',
        'hargalayanantambahan',
    ];
}