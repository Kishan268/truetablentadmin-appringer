<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSearchVisibilityInUserWorkProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_work_profiles', function (Blueprint $table) {
            \DB::statement("
                ALTER TABLE user_work_profiles CHANGE search_visibility search_visibility ENUM('0','1') DEFAULT '0'
            ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_work_profiles', function (Blueprint $table) {
            //
        });
    }
}
