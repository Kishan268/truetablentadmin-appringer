<?php

use Illuminate\Database\Seeder;

class RazorPaySeeder extends Seeder
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
        		'key' => 'profile_view_count_bandwidth',
        		'value' => '60',
        	],
        	[
        		'key' => 'profile_view_show_bandwidth',
        		'value' => '90',
        	],
        	[
        		'key' => 'RZP_KEY',
        		'value' => 'rzp_test_4AQUUMV01l3O1Z',
        	],
        	[
        		'key' => 'RZP_SECRET',
        		'value' => 'MwSmMS4ifPjl16gb1s7J4l4E',
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
