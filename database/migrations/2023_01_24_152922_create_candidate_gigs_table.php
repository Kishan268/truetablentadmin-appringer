<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateGigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_gigs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('gig_id');
            $table->longText('recruiter_msg')->nullable();
            $table->boolean('applied')->default(false);
            $table->boolean('saved')->default(false);
            $table->string('recruiter_action')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('candidate_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('gig_id')->references('id')->on('company_gigs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_gigs');
    }
}
