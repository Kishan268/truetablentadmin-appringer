<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSettings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_view_ttcash',
        'evaluation_view_ttcash',
        'fb',
        'instagram',
        'twitter',
        'linkedin',
        'contact_text',
        'company_name',
        'company_website',
        'company_email',
        'company_address',
        'company_phone',
        'featured_jobs_order'
    ];

    public static function getSystemSettings()
    {
        $SystemSettings = new SystemSettings();
        return SystemSettings::select('*')->get();
    }


}
