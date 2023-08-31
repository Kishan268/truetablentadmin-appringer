<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyJobRenewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_job_renews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_job_id');
            $table->dateTime('renew_date');
            $table->unsignedBigInteger('renew_by');
            $table->timestamps();

            $table->foreign('company_job_id')->references('id')->on('company_jobs')->onDelete('cascade');
            $table->foreign('renew_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_job_renews');
    }
}
