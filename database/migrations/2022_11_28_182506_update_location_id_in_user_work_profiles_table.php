<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLocationIdInUserWorkProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_work_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('joining_preference_id')->change();

            //$table->dropForeign(['location_id']);
            //$table->dropForeign(['work_authorization_id']);
            //$table->dropForeign(['joining_preference_id']);

            $table->foreign('location_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('work_authorization_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('joining_preference_id')->references('id')->on('master_data')->onDelete('restrict');
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
