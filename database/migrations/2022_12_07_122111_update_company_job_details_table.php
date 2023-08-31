<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCompanyJobDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\DB::table('job_additional_skills')->get() AS $value) {
            \DB::table('company_job_details')->insert([
                'company_job_id' => $value->job_id,
                'data_id'        => $value->skill_id,
                'type'           => 'additional_skills',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ]);
        }
        foreach (\DB::table('job_benefits')->get() AS $value) {
            \DB::table('company_job_details')->insert([
                'company_job_id' => $value->job_id,
                'data_id'        => $value->job_benefit_id,
                'type'           => 'benefits',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ]);
        }

        foreach (\DB::table('job_locations')->get() AS $value) {
            \DB::table('company_job_details')->insert([
                'company_job_id' => $value->job_id,
                'data_id'        => $value->location_id,
                'type'           => 'locations',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ]);
        }

        foreach (\DB::table('job_required_skills')->get() AS $value) {
            \DB::table('company_job_details')->insert([
                'company_job_id' => $value->job_id,
                'data_id'        => $value->skill_id,
                'type'           => 'required_skills',
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            ]);
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
