<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToUserWorkProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_work_profile_details', function (Blueprint $table) {
            if (!Schema::hasColumn('user_work_profile_details', 'user_id')) {
                $table->bigInteger('user_id')->unsigned()->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('users');
            }
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
