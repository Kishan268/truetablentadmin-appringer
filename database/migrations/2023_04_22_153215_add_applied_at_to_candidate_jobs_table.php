<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppliedAtToCandidateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_jobs', function (Blueprint $table) {
            $table->dateTime('applied_at')->after('recruiter_action');
            $table->dateTime('saved_at')->after('applied_at');
        });

        DB::statement("UPDATE candidate_jobs SET applied_at = created_at");
        DB::statement("UPDATE candidate_jobs SET saved_at = created_at");
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
