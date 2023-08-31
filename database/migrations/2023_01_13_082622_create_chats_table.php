<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('recruiter_id');
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('candidate_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recruiter_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('chats');
    }
}
