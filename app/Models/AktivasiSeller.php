<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivasiSeller extends Model
{
    use HasFactory;

    protected $table = 'aktivasi_sellers';

    protected $fillable = [
        'kantor',
        'tanggal',
        'nama_olshop',
        'jenis_aktivasi_seller',
        'nama_pemilik',
        'alamat_lengkap',
        'nomor_hp',
        'jenis_produk',
        'pesaing',
        'link_toko',
        'keterangan_lainnya',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'foto' => 'array',
    ];
}
