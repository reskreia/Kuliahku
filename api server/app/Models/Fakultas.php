<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $fillable = [
        'nama'
    ];
    
    public function dosen()
    {
        return $this->hasMany('App\Dosen', 'fakultas');
    }

    public function user()
    {
        return $this->hasMany('App\User', 'fakultas');
    }
}
