<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteInReportedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reported_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('reported_jobs', 'deleted_at')) {
                $table->softDeletes();
            }
            //$table->dropForeign(['user_id']);
            //$table->dropForeign(['job_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('reported_jobs', function (Blueprint $table) {
            //
        });
    }
}
