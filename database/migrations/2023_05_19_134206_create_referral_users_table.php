<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referral_id');
            $table->unsignedBigInteger('referred_by');
            $table->unsignedBigInteger('referred_to')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('referral_id')->references('id')->on('referrals')->onDelete('cascade');
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referred_to')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referral_users');
    }
}
