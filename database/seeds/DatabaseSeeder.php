<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use TruncateTable;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        Model::unguard();

        $this->truncateMultiple([
            'cache',
            'failed_jobs',
            'ledgers',
            'jobs',
            'sessions',
        ]);

        $this->call(AuthTableSeeder::class);

        Model::reguard();
        
        DB::table('lookups')->insert([
            ['name' => "Experiences", 'display_name' => 'Experiences', 'key' => 'EX', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "Education", 'display_name' => 'Education', 'key' => 'ED', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "Status", 'display_name' => 'Status', 'key' => 'ST', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "UserType", 'display_name' => 'UserType', 'key' => 'UT', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "Certification", 'display_name' => 'Certification', 'key' => 'CR', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "Evaluator Rating", 'display_name' => 'Evaluator Rating', 'key' => 'ER', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "Skill Rating", 'display_name' => 'Skill Rating', 'key' => 'SR', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "SalaryType", 'display_name' => 'SalaryType', 'key' => 'SA', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "Cities", 'display_name' => 'Cities', 'key' => 'LOC', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "Skills", 'display_name' => 'Skills', 'key' => 'SK', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()],
            ['name' => "Work Authorization", 'display_name' => 'Work Authorization', 'key' => 'WA', 'created_at' => \Carbon\Carbon::now()->toDateTimeString(), 'updated_at' => \Carbon\Carbon::now()->toDateTimeString()]
        ]);
    }
}
