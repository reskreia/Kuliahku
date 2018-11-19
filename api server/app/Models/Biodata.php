<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Biodata extends Model
{
     protected $fillable = [
        'asal',
        'lahir',
        'keterangan',
        'hp',
        'kelamin',
        'bekerja',
        'nim'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function akademik()
    {
        return $this->belongsTo(Akademik::class);
    }
}
