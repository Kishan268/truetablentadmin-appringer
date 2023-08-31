<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMasterDataInCompanyJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_jobs', function (Blueprint $table) {
            // $table->dropForeign(['user_id']);
            // $table->dropForeign(['company_id']);
            // $table->dropForeign(['job_type_id']);
            // $table->dropForeign(['salary_type_id']);
            // $table->dropForeign(['industry_domain_id']);
            // $table->dropForeign(['work_authorization_id']);
            // $table->dropForeign(['joining_preference_id']);
            // $table->dropForeign(['job_duration_id']);


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('job_type_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('salary_type_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('industry_domain_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('work_authorization_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('joining_preference_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('job_duration_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_jobs', function (Blueprint $table) {
            //
        });
    }
}
