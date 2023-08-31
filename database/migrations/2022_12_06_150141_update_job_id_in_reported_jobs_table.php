<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateJobIdInReportedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reported_jobs', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            // $table->dropForeign(['issue_id']);

            $table->foreign('job_id')->references('id')->on('company_jobs')->onDelete('cascade');
            $table->foreign('issue_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reported_jobs', function (Blueprint $table) {
            //
        });
    }
}
