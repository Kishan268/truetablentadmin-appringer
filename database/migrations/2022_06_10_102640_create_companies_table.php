<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('logo')->nullable();
            $table->longText('description')->nullable();
            $table->string('website')->unique();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('size_id')->unsigned()->nullable();
            $table->bigInteger('industry_domain_id')->unsigned()->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->boolean('equal_opportunity_employer')->default(false);
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('size_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('location_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('industry_domain_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
