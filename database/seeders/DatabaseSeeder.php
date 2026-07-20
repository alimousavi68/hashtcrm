<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'ادمین سیستم',
            'email' => 'admin@test.com',
            'phone' => '09120000000',
            'role' => 'admin',
        ]);

        $client = User::factory()->create([
            'name' => 'مشتری تست',
            'email' => 'client@test.com',
            'phone' => '09121111111',
            'role' => 'client',
        ]);

        \App\Models\Project::create([
            'client_id' => $client->id,
            'title' => 'پلتفرم تجارت الکترونیک هشت',
            'status' => 'brief',
            'feedback_deadline' => now()->addDays(7),
        ]);
    }
}
