<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddedFromOptionInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_in_users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users CHANGE COLUMN added_from added_from ENUM('frontend', 'csv', 'cron') NOT NULL DEFAULT 'frontend'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_in_users', function (Blueprint $table) {
            //
        });
    }
}
