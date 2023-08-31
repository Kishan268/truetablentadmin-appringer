<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWorkProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_work_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->text('contact_number')->nullable();
            $table->string('cv_link')->nullable();
            $table->text('summary')->nullable();
            $table->integer('total_experience')->nullable();
            $table->bigInteger('work_authorization_id')->unsigned()->nullable();
            $table->longText('evaluation_feedback')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('work_authorization_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_work_profiles');
    }
}
