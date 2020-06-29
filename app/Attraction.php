<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
    protected $fillable = ['name', 'city_id'];

    /**
     * Get the attractions for the city.
     */
    public function city()
    {
        return $this->belongsTo('App\City');
    }
}
