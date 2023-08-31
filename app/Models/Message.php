<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['chat_id','sender_id','message','is_blocked_message'];

    public function medias(){
    	return $this->hasMany('App\Models\MessageMedia');
    }

    public function getCreatedAtAttribute($value){
        return date("h:i A", strtotime($value));
    }


    public static function add($data)
    {
        try {
            $message = new Message();

            $message->fill($data)->save();

            return $message;
            
        } catch (Exception $e) {
            return '';
            
        }
        
    }

    public function sender(){
        return $this->hasOne('App\Models\Auth\User','id','sender_id');
    }

    public static function getLatestMessage($chat_id, $user_id)
    {
        return  Message::where('chat_id', $chat_id)->where(function ($query) use ($user_id) {


                    $query->where(function ($query) use ($user_id) {
                        $query->where('is_blocked_message', '1');
                        $query->where('sender_id', $user_id);
                    });

                    $query->orWhere(function ($query) use ($user_id) {
                        $query->where('is_blocked_message', '0');
                    });
                })->orderByDesc('id')->first();
    }

    public static function getChatLastMessage($chat_id, $user_id)
    {
        return  Message::where('chat_id', $chat_id)->orderByDesc('id')->first();
    }
}
