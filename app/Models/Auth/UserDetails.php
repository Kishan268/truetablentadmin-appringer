<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User Details.
 */
class UserDetails extends Model
{
   	protected $table = 'user_details';

   	/**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'telecommute' => 'boolean'
    ];

   	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id',
      'job_type',
      'preferred_location',
      'min_salary',
      'telecommute',
      'notification_new_jobs',
      'notification_profile_viewed',
      'blocked_companies'
    ];

    public function getJobTypeAttribute($value){
      return explode('|', $value);
    }

    public function getPreferredLocationAttribute($value){
      return explode('|', $value);
    }

    public static function getUserDetails($user_id)
    {
        try {
            $details = UserDetails::where('user_id',$user_id)->first();
            return $details;
        } catch (Exception $e) {
            return arrray();
            
        }
      
    }

    public static function updateUserDetails($data, $user_id)
    {
      $UserDetails = UserDetails::where('user_id', $user_id)->get();
      if ($UserDetails) {
        $user_details = UserDetails::where('user_id', $user_id)->update($data);
      }
      else{
        $user_details = UserDetails::insert(array_merge($data, ['user_id' => $user_id]));
      }
      return UserDetails::getUserDetails($user_id);
    }
}
