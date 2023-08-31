<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyGigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_gigs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->longText('description');
            $table->unsignedBigInteger('gig_type_id');
            $table->double('min_budget', 16, 2)->nullable();
            $table->double('max_budget', 16, 2)->nullable();
            $table->enum('status', ['published', 'draft', 'expired', 'closed', 'delete'])->default('published')->nullable();
            $table->bigInteger('close_reason_id')->unsigned()->nullable();
            $table->longText('close_reason_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('close_reason_id')->references('id')->on('master_data')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('gig_type_id')->references('id')->on('master_data')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_gigs');
    }
}
