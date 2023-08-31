<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRenewInCompanyJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('renew_by')->nullable()->after('updated_at');
            $table->dateTime('renew_date')->after('updated_at');

            $table->foreign('renew_by')->references('id')->on('users');
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
