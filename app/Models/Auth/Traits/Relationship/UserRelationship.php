<?php

namespace App\Models\Auth\Traits\Relationship;

use App\Models\Auth\PasswordHistory;
use App\Models\Auth\SocialAccount;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    // public function providers()
    // {
    //     return $this->hasMany(SocialAccount::class);
    // }

    /**
     * @return mixed
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function view_transactions()
    {
        return $this->hasMany('App\Models\ProfileViewTransactions', 'user_id', 'id');
    }
}
