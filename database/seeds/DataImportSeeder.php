<?php

use Illuminate\Database\Seeder;

class DataImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_configs')->insert([
            'key'        => 'data_synced_id',
            'value'      => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('system_configs')->insert([
            'key'        => 'data_per_cycle',
            'value'      => 50,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
