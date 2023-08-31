<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileMatchInCandidateJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_jobs', function (Blueprint $table) {
            $table->enum('is_profile_match',['0','1'])->default('0')->after('recruiter_action');
            $table->unsignedBigInteger('reason_id')->nullable();
            $table->string('recruiter_comment')->nullable();
            $table->foreign('reason_id')->references('id')->on('master_data');
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
