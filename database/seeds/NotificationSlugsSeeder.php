<?php

use Illuminate\Database\Seeder;

class NotificationSlugsSeeder extends Seeder
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
                'key' => 'is_email_notification_enabled',
                'value' => false,
            ],
            [
                'key' => 'is_sms_notification_enabled',
                'value' => false,
            ],
            [
                'key' => 'is_whatapp_notification_enabled',
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
