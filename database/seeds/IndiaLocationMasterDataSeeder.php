<?php

use Illuminate\Database\Seeder;

class IndiaLocationMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_data')->insert([
            'id'          => 1,
            'type'          => 'location',
            'name'          => 'India',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
