<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedGigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured_gigs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gig_id');
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('featured_gigs');
    }
}
