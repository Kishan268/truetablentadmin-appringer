<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCandidateIdInCandidateJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_jobs', function (Blueprint $table) {
            // $table->dropForeign(['candidate_id']);
            // $table->dropForeign(['job_id']);

            $table->foreign('candidate_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('company_jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_jobs', function (Blueprint $table) {
            //
        });
    }
}
