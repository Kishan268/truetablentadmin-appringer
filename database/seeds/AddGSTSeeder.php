<?php

use Illuminate\Database\Seeder;

class AddGSTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_configs')->insert([
            'key'        => 'gst',
            'value'      => 18,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('system_configs')->insert([
            'key'        => 'invoice_company_name',
            'value'      => 'FINDR PRO TECHNOLOGY SOLUTIONS PRIVATE LIMITED',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('system_configs')->insert([
            'key'        => 'invoice_company_gst_number',
            'value'      => '29AAFCF0800P1ZH',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
