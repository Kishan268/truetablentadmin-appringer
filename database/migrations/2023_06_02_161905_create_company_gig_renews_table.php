<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyGigRenewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_gig_renews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_gig_id');
            $table->dateTime('renew_date');
            $table->unsignedBigInteger('renew_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_gig_id')->references('id')->on('company_gigs')->onDelete('cascade');
            $table->foreign('renew_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_gig_renews');
    }
}
