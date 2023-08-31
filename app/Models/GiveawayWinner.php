<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiveawayWinner extends Model
{
	protected $fillable = ['category','start_date','end_date','candidate_name','candidate_email','candidate_location','registration_date'];
}
