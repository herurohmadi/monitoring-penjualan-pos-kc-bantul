<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canvasing extends Model
{
    use HasFactory;

    protected $table = 'canvasings';

    protected $fillable = [
        'kantor',
        'tanggal',
        'jenis_canvasing',
        'keterangan',
        'foto',
    ];


    protected $casts = [
        'tanggal' => 'datetime',
        'foto' => 'array',
    ];
}
