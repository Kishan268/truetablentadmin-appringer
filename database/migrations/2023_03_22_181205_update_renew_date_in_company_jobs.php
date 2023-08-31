<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRenewDateInCompanyJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_jobs', function (Blueprint $table) {
            DB::statement('UPDATE company_jobs SET renew_date = created_at;');
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
