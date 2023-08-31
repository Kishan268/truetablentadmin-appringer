<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class MessageMedia extends Model
{
    protected $fillable = ['chat_id','message_id','type', 'path', 'original_name', 'size_mb'];

    public function getPathAttribute($value){
        return ($value != null && $value != '') ? \App\Helpers\SiteHelper::getObjectUrl($value) : $value;
    }

    public static function add($data)
    {
        try {
            $MessageMedia = new MessageMedia();

            $MessageMedia->fill($data)->save();

            return $MessageMedia;
            
        } catch (Exception $e) {
            return '';
            
        }
        
    }
}
