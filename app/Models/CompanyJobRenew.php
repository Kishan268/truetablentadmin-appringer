<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompanyJobRenew extends Model
{

    protected $table = 'company_job_renews';
    
    protected $fillable = ['company_job_id','renew_date', 'renew_by'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    
}
