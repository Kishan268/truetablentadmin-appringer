<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
     protected $fillable = [
        'Name',
        'key',
        'subject',
        'mail_body',
        'sms_body',
        'wa_body',
        'is_mail_enabled',
        'is_sms_enabled',
        'is_wa_enabled',
        'variables',
        'created_by',
        'updated_by'
    ];

    public static function getValue($key)
    {
        $value = NotificationSetting::where('key',$key)->first();
        return $value ? $value : '';
    }

}
