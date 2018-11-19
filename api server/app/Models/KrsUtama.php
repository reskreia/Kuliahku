<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KrsUtama extends Model
{

    protected $fillable = [
        'semester_id',
        'makul_id',
        'nilai',
        'nim'
    ];

    public function makul()
    {
        return $this->hasMany('App\Models\Makul', 'makul_id', 'id');
    }

    public function semester()
    {
        return $this->hasMany('App\Models\Semester', 'semester_id', 'id');
    }

    public function ulang()
    {
        return $this->belongsTo(Ulang::class);
    }
}
