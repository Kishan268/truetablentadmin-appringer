<?php

namespace App\Models\Auth;

use Altek\Accountant\Contracts\Recordable;
use Altek\Accountant\Recordable as RecordableTrait;
use Altek\Eventually\Eventually;
use App\Models\Auth\Traits\SendUserPasswordReset;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

/**
 * Class User.
 */
abstract class BaseUser extends Authenticatable implements Recordable
{
    use HasRoles,
        Eventually,
        Impersonate,
        Notifiable,
        RecordableTrait,
        SendUserPasswordReset,
        SoftDeletes,
        Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'company_id',
        'avatar_type',
        'avatar_location',
        'password',
        'password_changed_at',
        'active',
        'confirmation_code',
        'confirmed',
        'timezone',
        'last_login_at',
        'last_login_ip',
        'to_be_logged_out',
        'date_of_birth',
        'gender',
        'contact',
        'is_preferred_skills',
        'min_salary',
        'notification_new_jobs',
        'notification_profile_viewed',
        'added_from',
        'email_verified_at',
        'provider_id',
        'provider_name',
        'provider_json',
        'otp',
        'email_otp',
        'is_mobile_verified',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     * @var array
     */
    protected $appends = [
        'full_name', 'role_type', 'is_profile_viewed_by_user', 'is_profile_viewed_by_company_user', 'uid'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'confirmed' => 'boolean',
        'to_be_logged_out' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_login_at',
        'password_changed_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Return true or false if the user can impersonate an other user.
     *
     * @param void
     * @return  bool
     */
    public function canImpersonate()
    {
        return $this->isAdmin();
    }

    /**
     * Return true or false if the user can be impersonate.
     *
     * @param void
     * @return  bool
     */
    public function canBeImpersonated()
    {
        return $this->id !== 1;
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->created_by = is_object(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : is_object(Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : NULL;
    //         $model->updated_by = NULL;
    //     });

    //     static::updating(function ($model) {
    //         $model->updated_by = is_object(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : is_object(Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : NULL;
    //     });

    //     static::deleting(function ($model) {
    //         $model->deleted_by = is_object(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : is_object(Auth::guard('web')->user()) ? Auth::guard('web')->user()->id : NULL;
    //     });
    // }
}
