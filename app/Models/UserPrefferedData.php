<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPrefferedData extends Model
{
	protected $fillable = ['user_id', 'type', 'data_id'];

    public function data()
    {
        return $this->belongsTo(MasterData::class, 'data_id');
    }

    public static function addData($user_id, $type, $data)
    {
        UserPrefferedData::where('user_id',$user_id)->where('type',$type)->delete();
        foreach ($data as $id) {
            UserPrefferedData::create([
                'user_id' => $user_id,
                'type'    => $type,
                'data_id' => $id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function getData($user_id,$type)
    {
        return UserPrefferedData::where('user_id',$user_id)->where('type',$type)->pluck('data_id')->toArray();
    }
}
