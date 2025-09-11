<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamarfoto extends Model
{
    protected $table = 'kamarfoto';

    protected $primaryKey = 'idkamarfoto';

    public $timestamps = false;

    protected $fillable = [
        'idkamar',
        'foto',
    ];
}