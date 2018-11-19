<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusMakul extends Model
{
    protected $fillable = [
        'wp'
    ];

    public function makul()
    {
        return $this->hasMany('App\Models\Makul', 'status', 'id');
    }
}
