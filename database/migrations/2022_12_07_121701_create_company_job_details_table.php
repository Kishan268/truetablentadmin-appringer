<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyJobDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_job_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_job_id');
            $table->unsignedBigInteger('data_id');
            $table->enum('type', ['required_skills', 'additional_skills', 'locations', 'benefits']);
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('company_job_id')->references('id')->on('company_jobs')->onDelete('cascade');
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
        Schema::dropIfExists('company_job_details');
    }
}
