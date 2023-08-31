<?php

use Illuminate\Database\Seeder;

class Phase6Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [
        	[
        		'key' => 'keyword_splitters',
        		'value' => '@0#',
        	],
        	[
        		'key' => 'remove_skill_enabled',
        		'value' => true,
        	],
        	[
        		'key' => 'data_pull_enabled',
        		'value' => true,
        	],
        	[
        		'key' => 'update_search_visibility_enabled',
        		'value' => true,
        	],
        	[
        		'key' => 'update_cv_enabled',
        		'value' => true,
        	],
        	[
        		'key' => 'user_search_visibility_synced_id',
        		'value' => 0,
        	],
            [
                'key' => 'update_jobs_enabled',
                'value' => true,
            ],
            [
                'key' => 'update_gigs_enabled',
                'value' => true,
            ]
        ];
        foreach ($values as $value) {
            DB::table('system_configs')->insert([
                'key'        => $value['key'],
                'value'      => $value['value'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
