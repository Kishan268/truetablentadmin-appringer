<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyGigDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_gig_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_gig_id');
            $table->unsignedBigInteger('data_id');
            $table->enum('type', ['required_skills', 'additional_skills', 'locations']);
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('company_gig_id')->references('id')->on('company_gigs')->onDelete('cascade');
            $table->foreign('data_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_gig_details');
    }
}
