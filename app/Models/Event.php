<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // has many workshops
    public function workshops()
    {
        return $this->hasMany(Workshop::class);
    }
}
