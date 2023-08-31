<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteInJobRequiredSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_required_skills', function (Blueprint $table) {
            if (!Schema::hasColumn('job_required_skills', 'deleted_at')) {
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
        Schema::table('job_required_skills', function (Blueprint $table) {
            //
        });
    }
}
