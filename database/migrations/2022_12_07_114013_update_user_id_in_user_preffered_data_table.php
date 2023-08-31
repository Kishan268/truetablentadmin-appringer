<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserIdInUserPrefferedDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_preffered_data', function (Blueprint $table) {
            \DB::statement('ALTER TABLE `user_preffered_data` CHANGE `user_id` `user_id` BIGINT(20) UNSIGNED NOT NULL;');
            \DB::statement('ALTER TABLE `user_preffered_data` CHANGE `data_id` `data_id` BIGINT(20) UNSIGNED NOT NULL;');
            
            //$table->dropForeign(['user_id']);
            //$table->dropForeign(['data_id']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('data_id')->references('id')->on('master_data');
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
