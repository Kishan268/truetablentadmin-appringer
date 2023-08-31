<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $fillable = ['name'];

    public static function getJobType()
    {
    	return JobType::select('id','name')->get();
    }
}
