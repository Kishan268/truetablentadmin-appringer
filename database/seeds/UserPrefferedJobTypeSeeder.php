<?php

use Illuminate\Database\Seeder;

class UserPrefferedJobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("insert into user_preffered_data (user_id, type,data_id) select id, 'job_types',4 from users where id not in (select user_id from user_preffered_data where type='job_types')");
    }
}
