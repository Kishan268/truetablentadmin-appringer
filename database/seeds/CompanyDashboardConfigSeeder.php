<?php

use Illuminate\Database\Seeder;

class CompanyDashboardConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_configs')->insert([
            'key'        => 'is_company_dashboard_enabled',
            'value'      => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
