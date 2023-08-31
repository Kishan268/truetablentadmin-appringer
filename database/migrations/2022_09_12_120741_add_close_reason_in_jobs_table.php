<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloseReasonInJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('company_jobs', 'close_reason_id')) {
                $table->bigInteger('close_reason_id')->unsigned()->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('company_jobs', 'close_reason_description')) {
                $table->longText('close_reason_description')->nullable()->after('close_reason_id');
            }
            $table->dropForeign(['close_reason_id']);
            $table->foreign('close_reason_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
}
