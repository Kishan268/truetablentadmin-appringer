<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    protected $fillable = ['city', 'state', 'zipcode'];
    protected $table = 'locations';

    public static function getLocations()
    {
    	return Locations::select('id','city')->get();
    }
}
