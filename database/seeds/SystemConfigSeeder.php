<?php

use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
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
        		'key' => 'currency',
        		'value' => 'Rs',
        	],
        	[
        		'key' => 'is_graph_enabled',
        		'value' => false,
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
