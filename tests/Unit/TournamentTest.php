<?php

namespace Tests\Unit;

use App\DataObjects\GroupResult;
use App\Models\Group;
use App\Models\Team;
use App\Services\GameService;
use Tests\TestCase;

class TournamentTest extends TestCase
{
    public function test_group_results()
    {
        // get all games of group A by date
        $group = Group::where('name', 'Group F')->firstOrFail();
        $games = $group->games;

        $this->assertEquals(6, count($games));

        $scores = [
            0 => [
                0 => 0, // Morocco
                1 => 1, // Croatia
            ],
            1 => [
                0 => 4, // Belgium
                1 => 0 // Canada
            ],
            2 => [
                0 => 1, // Belgium
                1 => 0 // Morocco
            ],
            3 => [
                0 => 4, // Croatia
                1 => 0 // Canada
            ],
            4 => [
                0 => 1, // Croatia
                1 => 4 // Belgium
            ],
            5 => [
                0 => 2, // Canada
                1 => 2 // Morocco
            ]
        ];

        $result = [
            Team::where('name', 'Belgium')->firstOrFail()->id => [
                'place' => 1,
                'team' => Team::where('name', 'Belgium')->firstOrFail()->name,
                'played' => 3,
                'won' => 3,
                'lost' => 0,
                'draw' => 0,
                'goals' => 9,
                'goals_against' => 1,
                'goals_diff' => 8,
                'points' => 9,
            ],
            Team::where('name', 'Croatia')->firstOrFail()->id => [
                'place' => 2,
                'team' => Team::where('name', 'Croatia')->firstOrFail()->name,
                'played' => 3,
                'won' => 2,
                'lost' => 1,
                'draw' => 0,
                'goals' => 6,
                'goals_against' => 4,
                'goals_diff' => 2,
                'points' => 6,
            ],
            Team::where('name', 'Morocco')->firstOrFail()->id => [
                'place' => 3,
                'team' => Team::where('name', 'Morocco')->firstOrFail()->name,
                'played' => 3,
                'won' => 0,
                'lost' => 2,
                'draw' => 1,
                'goals' => 2,
                'goals_against' => 4,
                'goals_diff' => -2,
                'points' => 1,
            ],
            Team::where('name', 'Canada')->firstOrFail()->id => [
                'place' => 4,
                'team' => Team::where('name', 'Canada')->firstOrFail()->name,
                'played' => 3,
                'won' => 0,
                'lost' => 2,
                'draw' => 1,
                'goals' => 2,
                'goals_against' => 10,
                'goals_diff' => -8,
                'points' => 1,
            ],
        ];

        $i = 0;

        // set preset scores
        foreach ($games as $game) {
            GameService::setScore($game, $scores[$i][0], $scores[$i][1]);

            $i++;
        }

        $groupResults = new GroupResult($group);

        // check group results
        $this->assertEquals($result, $groupResults->getResult());
    }
}
