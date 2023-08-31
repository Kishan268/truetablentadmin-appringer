<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;

class ChatMember extends Model
{
    use SoftDeletes;
    protected $fillable = ['chat_id', 'user_id', 'last_message_seen_id','last_message_deleted_id', 'status', 'block_reason_id', 'block_reason_comment', 'is_muted', 'mute_duration', 'muted_at'];
    
    public static function addMembers($chat_id,$user_id)
    {
        try {
            $ChatMember = new ChatMember();

            $ChatMember->chat_id = $chat_id;
            $ChatMember->user_id = $user_id;
            $ChatMember->save();
            return $ChatMember;
            
        } catch (Exception $e) {
            return '';
            
        }
        
    }

    public static function updateLastMessageSeen($chat_id, $message_id, $user_id)
    {
        try {
            $ChatMember = ChatMember::where('chat_id', $chat_id)->where('user_id',$user_id)->first();
            if ($ChatMember) {
                $ChatMember->last_message_seen_id = $message_id;
            } else {
                $ChatMember = new ChatMember();
                $ChatMember->last_message_seen_id = $message_id;
                $ChatMember->chat_id = $chat_id;
                $ChatMember->user_id = $user_id;
            }


            $ChatMember->save();

            return $ChatMember;
        } catch (Exception $e) {
            return '';
        }
    }

    public static function updateLastDeletedMessage($chat_id, $message_id, $user_id)
    {
        try {
            $ChatMember = ChatMember::where('chat_id', $chat_id)->where('user_id',$user_id)->first();
            if ($ChatMember) {
                $ChatMember->last_message_deleted_id = $message_id;
            } else {
                $ChatMember = new ChatMember();
                $ChatMember->last_message_deleted_id = $message_id;
                $ChatMember->chat_id = $chat_id;
                $ChatMember->user_id = $user_id;
            }


            $ChatMember->save();

            return $ChatMember;
        } catch (Exception $e) {
            return '';
        }
    }

    public static function getLastDeletedMessageId($chat_id, $user_id)
    {
        try {
            $ChatMember = ChatMember::where('chat_id', $chat_id)->where('user_id',$user_id)->first();
            if ($ChatMember) {
                return $ChatMember->last_message_deleted_id;
            } else {
                return '';
            }
        } catch (Exception $e) {
            return '';
        }
    }


    public static function udpdateStatus($chat_id, $request)
    {
        try {
            $ChatMember = ChatMember::where('chat_id', $chat_id)->where('user_id',$request->user_id)->first();
            if (!$ChatMember) {
                $ChatMember = new ChatMember();
            }

            $data = $request->only($ChatMember->getFillable());

            $ChatMember->fill($data)->save();

            return $ChatMember;
        } catch (\Exception $e) {
            return '';
        }
    }

    public static function getMessageReceiverData($chat_id, $sender_id)
    {
        try {
            $ChatMember = ChatMember::where('chat_id', $chat_id)->where('user_id','!=',$sender_id)->first();
            if ($ChatMember) {
                return $ChatMember;
            } else {
                return '';
            }
        } catch (Exception $e) {
            return '';
        }
    }
}
