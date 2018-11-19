<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriMakul extends Model
{
    protected $fillable = [
        'nama'
    ];

    public function makul()
    {
        return $this->hasMany('App\Models\Makul', 'kategori', 'id');
    }
}
