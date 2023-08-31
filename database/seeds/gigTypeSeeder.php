<?php

use Illuminate\Database\Seeder;

class gigTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_data')->insert([
            'type'          => 'gig_types',
            'name'          => 'Fixed Price',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('master_data')->insert([
            'type'          => 'gig_types',
            'name'          => 'Hourly',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
