<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progdi extends Model
{
    protected $fillable = [
        'jurusan_id',
        'fakultas_id'
    ];
    
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
