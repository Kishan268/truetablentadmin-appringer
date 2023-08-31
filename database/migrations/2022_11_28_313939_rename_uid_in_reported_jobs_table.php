<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameUidInReportedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reported_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('reported_jobs', 'uid')) {
                $table->renameColumn('uid', 'user_id');
            }
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
