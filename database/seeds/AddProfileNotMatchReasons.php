<?php

use Illuminate\Database\Seeder;

class AddProfileNotMatchReasons extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_data')->insert([
            'type'          => 'candidate_reject_reasons',
            'name'          => 'Location do not match',
            'order'          => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('master_data')->insert([
            'type'          => 'candidate_reject_reasons',
            'name'          => 'Skill do not match',
            'order'          => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('master_data')->insert([
            'type'          => 'candidate_reject_reasons',
            'name'          => 'Experience do not match',
            'order'          => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('master_data')->insert([
            'type'          => 'candidate_reject_reasons',
            'name'          => 'Other',
            'order'          => 4,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
