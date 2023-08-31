<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('user_type', ['candidate', 'companies']);
            $table->string('program_name')->nullable();
            $table->string('program_description')->nullable();
            $table->string('program_image')->nullable();
            $table->integer('limit_per_user')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('type', ['TT Cash'])->default('TT Cash');
            $table->double('amount', 16, 2);
            $table->integer('eligiblity_number')->nullable();
            

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referrals');
    }
}
