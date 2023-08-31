<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSearchVisibilityToUserWorkProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_work_profiles', function (Blueprint $table) {
            $table->enum('search_visibility', ['0', '1'])->nullable()->default('1')->after('searchable_hash');
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
