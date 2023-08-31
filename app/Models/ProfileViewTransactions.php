<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Auth\User;

class ProfileViewTransactions extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'profile_view_transactions';
    protected $fillable = ['candidate_id', 'company_id', 'user_id', 'type', 'amount', 'remaining', 'by', 'company_user_id', 'for', 'created_at', 'updated_at'];

    public function candidate(){
        return $this->hasOne('App\Models\Auth\User', 'id', 'candidate_id');
    }

    public function companyUser(){
        return $this->hasOne('App\Models\Auth\User', 'id', 'company_user_id');
    }

    public function scopeCompany($query){
        return $query->where('user_id', 0)->with('companyUser');
    }

    public function scopeUser($query){
        return $query->where('user_id', '!=', 0);
    }

    public function getCreatedDateAttribute($value){
        return Carbon::create($this->created_at)->format('d/m/Y');
    }

    public function getValidUntilAttribute(){
        return date('d-M-Y h:i:s', strtotime($this->created_at. ' + '.config('app.VIEWVALIDITYDAYS').' days'));
        // return Carbon::create($this->created_at)->addDays(config('app.VIEWVALIDITYDAYS'))->format('d-M-Y h:i:s');
    }

    public function getCandidateUidAttribute(){
        return $this->candidate ? $this->candidate->user_id : 'NA';
    }

    public function getCompanyUserUidAttribute(){
        return $this->companyUser ? $this->companyUser->user_id : 'NA';
    }

    public function getTransactionByAttribute(){
        return User::where('id',$this->by)->first()->full_name;
    }

    protected $appends = ['valid_until', 'candidate_uid', 'company_user_uid', 'transaction_by', 'created_date'];
}
