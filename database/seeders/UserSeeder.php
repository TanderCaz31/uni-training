<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Giona',
            'email' => 'tandercaz31@gmail.com',
            'role' => 'admin'
        ]);
        User::factory()->count(10)->create();
    }
}
