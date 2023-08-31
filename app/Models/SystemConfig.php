<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemConfig extends Model
{
    use SoftDeletes;
    protected $fillable = ['key', 'value'];

    public static function getValue($key)
    {
        $value = SystemConfig::where('key',$key)->first();

        return $value ? $value->value : '';
    }

    public static function updateValue($key,$value)
    {
        return SystemConfig::where('key',$key)->update(['value' => $value]);
    }

}
