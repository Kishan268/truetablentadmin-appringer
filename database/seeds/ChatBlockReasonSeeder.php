<?php

use Illuminate\Database\Seeder;

class ChatBlockReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_data')->insert([
            'type'          => 'chat_block_reasons',
            'name'          => 'Spam User',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
