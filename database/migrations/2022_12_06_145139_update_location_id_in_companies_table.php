<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLocationIdInCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->change();
            $table->unsignedBigInteger('industry_domain_id')->change();
            $table->unsignedBigInteger('size_id')->change();

            //$table->dropForeign(['size_id']);
            //$table->dropForeign(['location_id']);
            //$table->dropForeign(['industry_domain_id']);

            $table->foreign('size_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('location_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('industry_domain_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}
