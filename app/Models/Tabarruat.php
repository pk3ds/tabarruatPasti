<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tabarruat extends Model
{
    protected $fillable = [
        'nama_pemohon',
        'pasti',
        'nama_arwah',
        'sudah_sumbangan',
    ];

    protected $casts = [
        'nama_arwah' => 'array', // Automatically cast JSON to array
        'sudah_sumbangan' => 'boolean',
    ];
}
