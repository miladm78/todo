<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Task;
use App\Models\Todo;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory(10)
             ->has(Todo::factory()->count(10),'todos')
             ->create();

         \App\Models\User::factory()->create([
             'name' => 'milad majd',
             'email' => 'miladmajd2@gmail.com',
         ]);
    }
}
