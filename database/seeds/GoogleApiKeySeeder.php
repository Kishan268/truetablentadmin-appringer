<?php

use Illuminate\Database\Seeder;

class GoogleApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_configs')->insert([
            'key'        => 'google_api_key',
            'value'      => "AIzaSyB6j4SKNlafOtnCOwBlRVAy3QGXLl96sfc",
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
