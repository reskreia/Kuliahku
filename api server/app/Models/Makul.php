<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Makul extends Model {

    protected $fillable = [
        'jurusan',
        'semester',
        'kurikulum',
        'kode_mk',
        'nama_mk',
        'sks',
        'teori',
        'praktek',
        'status',
        'kategori',
        'prasyarat'
    ];

    public function status()
    {
        return $this->belongsTo(StatusMakul::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriMakul::class);
    }

    public function krsdata()
    {
        return $this->belongsTo(KrsData::class);
    }
}
