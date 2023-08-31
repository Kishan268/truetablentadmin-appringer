<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('designation')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('avatar_location')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('active')->unsigned()->default(1);
            $table->string('otp')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->tinyInteger('confirmed')->unsigned()->default(0);
            $table->date('date_of_birth')->nullable();
            $table->string('min_salary')->nullable();
            $table->enum('is_telecommute', ['0', '1'])->default('0');
            $table->tinyInteger('notification_new_jobs')->nullable();
            $table->tinyInteger('notification_profile_viewed')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->tinyInteger('to_be_logged_out')->default(0);
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
