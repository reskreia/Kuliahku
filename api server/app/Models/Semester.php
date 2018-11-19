<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{

    protected $fillable = [
        'nama',
        'angka',
        'keterangan',
        'cuti',
		'nim'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function krsdata()
    {
        return $this->belongsTo(KrsData::class);
    }

    public function makul()
    {
        return $this->belongsTo(Makul::class);
    }
}
