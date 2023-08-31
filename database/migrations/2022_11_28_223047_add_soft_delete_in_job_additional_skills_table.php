<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteInJobAdditionalSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_additional_skills', function (Blueprint $table) {
            if (!Schema::hasColumn('blocked_companies', 'deleted_at')) {
                $table->softDeletes();
            }
            //$table->dropForeign(['job_id']);

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
        Schema::table('job_additional_skills', function (Blueprint $table) {
            //
        });
    }
}
