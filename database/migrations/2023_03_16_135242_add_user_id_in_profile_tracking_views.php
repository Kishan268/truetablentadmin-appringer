<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdInProfileTrackingViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_tracking_views', function (Blueprint $table) {
            DB::statement('ALTER TABLE profile_view_transactions MODIFY user_id BIGINT(20) UNSIGNED NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_tracking_views', function (Blueprint $table) {
            //
        });
    }
}
