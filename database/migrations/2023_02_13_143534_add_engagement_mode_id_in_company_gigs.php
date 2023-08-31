<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEngagementModeIdInCompanyGigs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_gigs', function (Blueprint $table) {
            $table->unsignedBigInteger('engagement_mode_id')->after('gig_type_id')->nullable();
            $table->foreign('engagement_mode_id')->references('id')->on('master_data')->onDelete('restrict');
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
