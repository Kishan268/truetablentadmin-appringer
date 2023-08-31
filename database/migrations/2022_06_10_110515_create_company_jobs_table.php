<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('jobs') && !Schema::hasTable('company_jobs')) {
            Schema::create('company_jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title');
                $table->string('description');
                $table->unsignedBigInteger('job_type_id');
                $table->unsignedBigInteger('salary_type_id');
                $table->unsignedBigInteger('industry_domain_id')->nullable();
                $table->unsignedBigInteger('work_authorization_id');
                $table->enum('is_telecommute', ['0', '1'])->nullable();
                $table->string('minimum_experience_required')->nullable();
                $table->string('maximum_experience_required')->nullable();
                $table->enum('is_travel_required', ['0', '1'])->nullable();
                $table->unsignedBigInteger('joining_preference_id')->nullable();
                $table->unsignedBigInteger('job_duration_id')->nullable();
                $table->double('min_salary', 16, 2)->nullable();
                $table->double('max_salary', 16, 2)->nullable();
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('user_id');
                $table->enum('status', ['published', 'draft', 'expired', 'closed', 'delete'])->default('published')->nullable();
                $table->integer('travel_percentage')->nullable();
                $table->enum('eeo', ['0', '1'])->default('0')->nullable();
                $table->bigInteger('close_reason_id')->unsigned()->nullable();
                $table->longText('close_reason_description')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->foreign('close_reason_id')->references('id')->on('master_data')->onDelete('restrict');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                $table->foreign('job_type_id')->references('id')->on('master_data')->onDelete('restrict');
                $table->foreign('salary_type_id')->references('id')->on('master_data')->onDelete('restrict');
                $table->foreign('industry_domain_id')->references('id')->on('master_data')->onDelete('restrict');
                $table->foreign('work_authorization_id')->references('id')->on('master_data')->onDelete('restrict');
                $table->foreign('joining_preference_id')->references('id')->on('master_data')->onDelete('restrict');
                $table->foreign('job_duration_id')->references('id')->on('master_data')->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_jobs');
    }
}
