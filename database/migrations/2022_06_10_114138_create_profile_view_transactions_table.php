<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileViewTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('profile_view_transactions');
        
        Schema::create('profile_view_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->enum('type', ['credit', 'debit']);
            $table->bigInteger('amount')->default(0);
            $table->bigInteger('remaining')->default(0);
            $table->unsignedBigInteger('by');
            $table->unsignedBigInteger('candidate_id')->default(0);
            $table->unsignedBigInteger('company_user_id')->default(0);
            $table->string('for')->default('profile');
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_view_transactions');
    }
}
