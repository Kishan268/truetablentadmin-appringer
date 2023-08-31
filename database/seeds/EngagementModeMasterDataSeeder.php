<?php

use Illuminate\Database\Seeder;

class EngagementModeMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_data')->insert([
            'type'          => 'engagement_mode',
            'name'          => 'Remote',
            'order'          => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('master_data')->insert([
            'type'          => 'engagement_mode',
            'name'          => 'Hybrid',
            'order'          => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('master_data')->insert([
            'type'          => 'engagement_mode',
            'name'          => 'On-Site',
            'order'          => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
    }
}
