<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Message;
use App\Models\ChatMember;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;
    protected $fillable = ['candidate_id', 'job_id', 'recruiter_id', 'updated_at'];

    protected $appends = ['last_message', 'last_message_by', 'last_message_at', 'is_last_message_blocked_message', 'status', 'unseen_messages_count', 'is_muted', 'is_get_blocked'];


    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function medias()
    {
        return $this->hasMany('App\Models\MessageMedia', 'id', 'chat_id');
    }

    public function recruiter()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'recruiter_id');
    }

    public function candidate()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'candidate_id');
    }

    public function members()
    {
        return $this->hasMany('App\Models\ChatMember');
    }

    public function job()
    {
        return $this->hasOne('App\Models\Job', 'id', 'job_id');
    }

    public function getLastMessageAttribute()
    {

        $user_id = Auth::guard('api')->user()->id;

        $message = Message::getLatestMessage($this->id, $user_id);

        return $message ? $message->message : '';
    }

    public function getLastMessageByAttribute()
    {

        $user_id = Auth::guard('api')->user()->id;

        $message = Message::getLatestMessage($this->id, $user_id);

        return $message ? $message->sender_id : '';
    }

    public function getLastMessageAtAttribute()
    {
        $user_id = Auth::guard('api')->user()->id;

        $message = Message::getLatestMessage($this->id, $user_id);

        return $message ? Carbon::parse($message->created_at)->diffForHumans() : '';
    }

    public function getIsLastMessageBlockedMessageAttribute()
    {

        $user_id = Auth::guard('api')->user()->id;

        $message = Message::getLatestMessage($this->id, $user_id);

        return $message->is_blocked_message == '1' ? true : false;
    }

    public function getStatusAttribute()
    {
        $user_id = Auth::guard('api')->user()->id;
        return ChatMember::where('chat_id', $this->id)->where('user_id', $user_id)->first() ? ChatMember::where('chat_id', $this->id)->where('user_id', $user_id)->first()->status : '';
    }

    public function getUnseenMessagesCountAttribute()
    {
        $user_id = Auth::guard('api')->user()->id;

        $last_message_seen_id = ChatMember::where('chat_id', $this->id)->where('user_id', $user_id)->first() ? ChatMember::where('chat_id', $this->id)->where('user_id', $user_id)->first()->last_message_seen_id : '';
        $query = Message::where('chat_id', $this->id)->where('is_blocked_message','0');
        if ($last_message_seen_id == '') {
            return $query->count();
        } else {
            return $query->where('id', '>', $last_message_seen_id)->count();
        }
    }

    public function getIsMutedAttribute()
    {
        $user_id = Auth::guard('api')->user()->id;
        $chat_member_data = ChatMember::where('chat_id', $this->id)->where('user_id', $user_id)->first();
        if ($chat_member_data && isset($chat_member_data->is_muted) && $chat_member_data->is_muted != -1) {
            $mute_duration  = $chat_member_data->mute_duration;
            $mute_duration_ends =  date('Y-m-d H:i:s', strtotime($chat_member_data->muted_at . ' + ' . $mute_duration . ' days'));
            $current_date_time = date('Y-m-d H:i:s');
            if ($mute_duration_ends < $current_date_time) {
                $chat_member_data->is_muted = '0';
                $chat_member_data->mute_duration = null;
                $chat_member_data->muted_at = null;
                $chat_member_data->save();
            }
        }

        return ChatMember::where('chat_id', $this->id)->where('user_id', $user_id)->first() ? ChatMember::where('chat_id', $this->id)->where('user_id', $user_id)->first()->is_muted : '';
    }

    public function getIsGetBlockedAttribute()
    {
        $user_id = Auth::guard('api')->user()->id;
        return ChatMember::where('chat_id', $this->id)->where('user_id', '!=', $user_id)->where('status', 'block')->first() ? true : false;
    }

    public static function add($request)
    {
        try {
            $chat = new Chat();

            $data = $request->only($chat->getFillable());

            $chat->fill($data)->save();

            return $chat;
        } catch (\Exception $e) {
            return '';
        }
    }

    public static function edit($id, $request)
    {
        try {
            $chat = Chat::find($id);

            $data = $request->only($chat->getFillable());

            $chat->fill($data)->save();

            return $chat;
        } catch (Exception $e) {
            return '';
        }
    }

    public static function chatExists($request)
    {
        try {
            $query = Chat::where('candidate_id', $request->candidate_id)
                ->where('recruiter_id', $request->recruiter_id);

            if ($request->has('job_id') && $request->job_id != '') {
                $query->where('job_id', $request->job_id);
            } else {
                $query->where('job_id', null);
            }

            $chat = $query->first();
            if ($chat != null) {
                $chat->update(['updated_at' => date('Y-m-d H:i:s')]);
                return $chat->id;
            }

            return '';
        } catch (\Exception $e) {
            return '';
        }
    }

    public static function getUserUnreadMessagesCount($user_id)
    {
        try {
            return Message::select('chat_members.last_message_seen_id AS last_message_seen_id','messages.*',\DB::raw('COUNT(messages.id) as messages_count'))
                ->join('chats','messages.chat_id','chats.id')
                ->join('chat_members','chat_members.chat_id','chats.id')
                ->where('chat_members.user_id', $user_id)
                ->where('messages.is_blocked_message','!=', '1')
                ->where(function ($query) use ($user_id) {


                    $query->where(function ($query) use ($user_id) {
                        $query->where('chat_members.last_message_seen_id', NULL);
                    });

                    $query->orWhere(function ($query) use ($user_id) {
                        $query->where('chat_members.last_message_seen_id','!=' ,NULL);
                        $query->whereRaw('messages.id > last_message_seen_id');
                    });
                })->count();
        } catch (\Exception $e) {
            return '';
        }
    }
}
