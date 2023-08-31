<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPrefferedDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_preffered_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->enum('type', ['skills','locations','job_types']);
            $table->bigInteger('data_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('data_id')->references('id')->on('master_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_preffered_data');
    }
}
