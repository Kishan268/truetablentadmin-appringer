<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SystemSettings;


class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = ['PHP', 'Laravel', 'React JS'];
        foreach ($skills as $skill) {
            DB::table('master_data')->insert([
                'type'          => 'skills',
                'name'          => $skill,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $job_types = ['Full Time', 'Part Time', 'Corp-to-Corp'];
        foreach ($job_types as $job_type) {
            DB::table('master_data')->insert([
                'type'          => 'job_types',
                'name'          => $job_type,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $company_sizes = ['1-49', '50-199', '200-999', '1000-4999', '5000-above'];
        foreach ($company_sizes as $company_size) {
            DB::table('master_data')->insert([
                'type'          => 'company_sizes',
                'name'          => $company_size,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $industry_domains = ['Aerospace', 'Automotive', 'Banking', 'BFSI', 'Consumer / FMCG', 'Chemicals', 'Engineering and Construction', 'Energy', 'Education', 'Finance', 'Hospitality and Leisure', 'Healthcare', 'Insurance', 'Technology', 'Retail', 'Travel', 'Telecom'];
        foreach ($industry_domains as $industry_domain) {
            DB::table('master_data')->insert([
                'type'          => 'industry_domains',
                'name'          => $industry_domain,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $salary_types = ['Annual', 'Hourly'];
        foreach ($salary_types as $salary_type) {
            DB::table('master_data')->insert([
                'type'       => 'salary_types',
                'name'       => $salary_type,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $work_authorizations = ['Citizen', 'GC', 'H1B', 'H4 EAD', 'L2 EAD', 'TN Visa', 'F1 OPT(STEM)'];
        foreach ($work_authorizations as $work_authorization) {
            DB::table('master_data')->insert([
                'type'       => 'work_authorizations',
                'name'       => $work_authorization,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }


        $benefits = ['401K', 'Medical Insurance', 'Dental Insurance', 'Life Coverage', 'Maternity Leave', 'Paternity Leave'];
        foreach ($benefits as $benefit) {
            DB::table('master_data')->insert([
                'type'       => 'benefits',
                'name'       => $benefit,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $joining_preferences = ['Immediate', '1 Week', '2 Weeks', '1 Month'];
        foreach ($joining_preferences as $joining_preference) {
            DB::table('master_data')->insert([
                'type'       => 'joining_preferences',
                'name'       => $joining_preference,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $job_durations = ['Less than 3 Months', 'Less than 6 Months', '1 year & over'];
        foreach ($job_durations as $job_duration) {
            DB::table('master_data')->insert([
                'type'       => 'job_durations',
                'name'       => $job_duration,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $close_reasons = ['Post filled', 'This job has been cancelled', 'This position has been fulfilled', 'This position has been scrapped', 'This position has been deferred'];
        foreach ($close_reasons as $reason) {
            DB::table('master_data')->insert([
                'type'        => 'close_job_issues',
                'name'        => $reason,
                'description' => $reason,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        SystemSettings::create([
            'company_name' => env('APP_NAME', ''),
            'company_website' => env('FRONTEND_URL', '')
        ]);
    }
}
