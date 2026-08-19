<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungans';

    protected $fillable = [
        'kantor',
        'tanggal',
        'alamat_kunjungan',
        'jenis_kunjungan',
        'tujuan_kunjungan',
        'hasil_kunjungan',
        'keterangan_lainnya',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'foto' => 'array',
    ];
}
