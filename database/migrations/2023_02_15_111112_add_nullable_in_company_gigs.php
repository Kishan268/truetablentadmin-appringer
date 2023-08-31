<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableInCompanyGigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_gigs', function (Blueprint $table) {
            DB::statement('ALTER TABLE company_gigs MODIFY description longtext NULL;');
            DB::statement('ALTER TABLE company_gigs MODIFY gig_type_id BIGINT UNSIGNED NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_gigs', function (Blueprint $table) {
            //
        });
    }
}
