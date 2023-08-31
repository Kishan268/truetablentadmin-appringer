<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableInCompanyJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_jobs', function (Blueprint $table) {
            DB::statement('ALTER TABLE company_jobs MODIFY description longtext NULL;');
            DB::statement('ALTER TABLE company_jobs MODIFY job_type_id BIGINT UNSIGNED NULL;');
            DB::statement('ALTER TABLE company_jobs MODIFY salary_type_id BIGINT UNSIGNED NULL;');
            DB::statement('ALTER TABLE company_jobs MODIFY work_authorization_id BIGINT UNSIGNED NULL;');
            DB::statement('ALTER TABLE company_jobs MODIFY company_id BIGINT UNSIGNED NULL;');
            DB::statement('ALTER TABLE company_jobs MODIFY user_id BIGINT UNSIGNED NULL;');
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
