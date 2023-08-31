<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGigTypeInUserPrefferedData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_preffered_data', function (Blueprint $table) {
            DB::statement("ALTER TABLE user_preffered_data CHANGE COLUMN type type ENUM('skills','locations','job_types','gig_types') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_preffered_data', function (Blueprint $table) {
            //
        });
    }
}
