<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\League;
use App\Models\Tournament;
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
        $tournament = Tournament::updateOrCreate(
            [
                'title' => 'World Cup 2022'
            ],
            [
                'title' => 'World Cup 2022'
            ],
        );

        $league = League::updateOrCreate(
            [
                'title' => 'General',
                'is_general' => true,
            ],
            [
                'title' => 'General',
                'is_general' => true,
                'tournament_id' => $tournament->id,
            ],
        );
    }
}
