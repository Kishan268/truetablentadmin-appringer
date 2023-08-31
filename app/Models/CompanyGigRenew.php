<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyGigRenew extends Model
{

    protected $table = 'company_gig_renews';
    
    protected $fillable = ['company_gig_id','renew_date', 'renew_by'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    
}
