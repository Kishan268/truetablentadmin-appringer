<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArmedForcesInUserWorkProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_work_profiles', function (Blueprint $table) {
            $table->enum('her_career_reboot', ['0', '1'])->default('0')->nullable()->after('layoff');
            $table->enum('differently_abled', ['0', '1'])->default('0')->nullable()->after('layoff');
            $table->enum('armed_forces', ['0', '1'])->default('0')->nullable()->after('layoff');
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
