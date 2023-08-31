<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLayoffInWorkProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_work_profiles', function (Blueprint $table) {
            $table->enum('layoff', ['0', '1'])->default('0')->nullable()->after('summary');
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
