<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSkillIdInUserWorkProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_work_profile_details', function (Blueprint $table) {
            //$table->dropForeign(['user_work_profile_id']);
            //$table->dropForeign(['skill_id']);

            $table->foreign('user_work_profile_id')->references('id')->on('user_work_profiles')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_work_profile_details', function (Blueprint $table) {
            //
        });
    }
}
