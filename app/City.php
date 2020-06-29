<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the attractions for the city.
     */
    public function attractions()
    {
        return $this->hasMany('App\Attraction');
    }
}
