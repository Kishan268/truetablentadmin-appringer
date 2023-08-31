<?php

use Illuminate\Database\Seeder;

class AddChatGPTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_configs')->insert([
            'key'        => 'chatgpt_id',
            'value'      => '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',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
