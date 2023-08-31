<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockedDomain extends Model
{
	use SoftDeletes;
	protected $fillable = ['domain_name'];


	public static function checkDoamin($domain)
	{
		return BlockedDomain::where('domain_name',$domain)->first() ? true : false;
	}
}
