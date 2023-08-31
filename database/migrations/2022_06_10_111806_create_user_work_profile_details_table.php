<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWorkProfileDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_work_profile_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('user_work_profile_id')->unsigned();
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->longtext('description')->nullable();
            $table->string('awarded_by')->nullable();
            $table->integer('experience')->nullable();
            $table->string('from_date')->nullable();
            $table->string('to_date')->nullable();
            $table->enum('is_present', ['0', '1'])->default('0')->nullable();
            $table->bigInteger('skill_id')->unsigned();
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_work_profile_id')->references('id')->on('user_work_profiles')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_work_profile_details');
    }
}
