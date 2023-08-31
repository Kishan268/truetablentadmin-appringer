<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleLocation extends Model
{
    protected $fillable = ['name', 'place_id','lat','long'];


    public static function getLocations($q='')
    {
    	return $q != '' ? GoogleLocation::where('name', 'like', $q.'%')->get() : GoogleLocation::get();
    }
}
