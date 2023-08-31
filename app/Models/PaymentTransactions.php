<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransactions extends Model
{
    protected $fillable = ['company_id', 'user_id', 'amount', 'transaction_id', 'transaction_details'];

    public function company(){
		return $this->hasOne('App\Models\Companies', 'id', 'company_id');
	}

    public function user(){
		return $this->hasOne('App\Models\Auth\User', 'id', 'user_id');
	}
}
