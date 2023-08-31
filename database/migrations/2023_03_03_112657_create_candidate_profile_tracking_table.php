<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateProfileTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_profile_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('candidate_id')->unsigned();
            $table->bigInteger('recruiter_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->enum('is_profile_viewed', ['0', '1']);
            $table->enum('is_profile_downloaded', ['0', '1']);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('candidate_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recruiter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_profile_tracking');
    }
}
