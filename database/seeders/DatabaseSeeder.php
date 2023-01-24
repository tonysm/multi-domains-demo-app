<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->times(2)
            ->withPersonalTeam(Team::factory()->state(new Sequence(
                ['domain' => 'first-customer.localhost'],
                ['domain' => 'second-customer.localhost'],
            )))
            ->state(new Sequence(
                ['email' => 'first@example.com'],
                ['email' => 'second@example.com'],
            ))
            ->create();
    }
}
