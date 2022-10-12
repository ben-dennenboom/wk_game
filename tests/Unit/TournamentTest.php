<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Models\Group;
use App\Models\Stage;
use App\Models\Team;
use App\Models\Tournament;
use App\Services\GameService;
use App\Services\TournamentService;
use Tests\TestCase;

class TournamentTest extends TestCase
{
    public function test_group_results()
    {
        // get all games of group A
        $group = Group::where('name', 'Group F')->firstOrFail();
        $games = $group->games;

        $this->assertEquals(6, count($games));

        $scores = $this->getGroupScores();

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

        $groupResults = TournamentService::getGroupResults($group);

        // check group results
        $this->assertEquals($result, $groupResults->getResult());
    }

    public function test_game_is_calculated()
    {
        $tournament = Tournament::firstOrFail();
        $stage = Stage::firstOrFail();

        $team1 = Team::firstOrFail();
        $team2 = Team::skip(1)->firstOrFail();
        $team3 = Team::skip(2)->firstOrFail();
        $team4 = Team::skip(3)->firstOrFail();

        // create two games
        $game1 = Game::create(
            [
                'home_team_id' => $team1->id,
                'away_team_id' => $team2->id,
                'tournament_id' => $tournament->id,
                'stage_id' => $stage->id,
            ]
        );

        $game2 = Game::create(
            [
                'home_team_id' => $team3->id,
                'away_team_id' => $team4->id,
                'tournament_id' => $tournament->id,
                'stage_id' => $stage->id,
            ]
        );

        // create third game that is calculated by result of two games
        $game3 = Game::create(
            [
                'tournament_id' => $tournament->id,
                'stage_id' => $stage->id,
                'is_calculated' => 1,
                'home_from_winner_game_id' => $game1->id,
                'away_from_winner_game_id' => $game2->id,
            ]
        );

        $game4 = Game::create(
            [
                'tournament_id' => $tournament->id,
                'stage_id' => $stage->id,
                'is_calculated' => 1,
                'home_from_loser_game_id' => $game1->id,
                'away_from_loser_game_id' => $game2->id,
            ]
        );

        // play games & set scores
        GameService::setScore($game1, 2, 0);
        GameService::setScore($game2, 2, 0, 5, 0);

        $game3->refresh();
        $game4->refresh();

        GameService::setScore($game3, 2, 0);
        GameService::setScore($game4, 2, 0);

        // test
        $this->assertEquals($game1->winner_id, $game1->home_team_id);
        $this->assertEquals($game2->winner_id, $game2->home_team_id);

        $this->assertEquals($game3->home_team_id, $team1->id);
        $this->assertEquals($game3->away_team_id, $team3->id);
        $this->assertEquals($game3->winner_id, $team1->id);

        $this->assertEquals($game4->home_team_id, $team2->id);
        $this->assertEquals($game4->away_team_id, $team4->id);
        $this->assertEquals($game4->winner_id, $team2->id);
    }

    public function test_game_is_calculated_from_group()
    {
        // fill score of group en check if next game is calculated from group result
        $tournament = Tournament::firstOrFail();
        $stage = Stage::firstOrFail();
        $group = Group::where('name', 'Group F')->firstOrFail();
        $games = $group->games;

        $this->assertEquals(6, count($games));

        $scores = $this->getGroupScores();

        $i = 0;

        // set preset scores
        foreach ($games as $game) {
            GameService::setScore($game, $scores[$i][0], $scores[$i][1]);

            $i++;
        }

        // create third game that is calculated by result of two games
        $game = Game::create(
            [
                'tournament_id' => $tournament->id,
                'stage_id' => $stage->id,
                'is_calculated' => 1,
                'get_home_from_slug' => '1F',
                'get_away_from_slug' => '2F',
            ]
        );

        // calculate group result and linked games
        $groupResult = TournamentService::getGroupResults($group);
        TournamentService::calculateGamesFromGroup($group);

        $game->refresh();

        GameService::setScore($game, 1, 0);

        // test
        $this->assertEquals($game->home_team_id, $groupResult->getFirstTeam()->id);
        $this->assertEquals($game->away_team_id, $groupResult->getSecondTeam()->id);
        $this->assertEquals($game->winner_id, $groupResult->getFirstTeam()->id);
    }

    public function test_full_tournament()
    {
        // Simulate full tournament where Belgium always wins
        // winner should be Belgium

        $winner = Team::where('name', 'Belgium')->firstOrFail();

        $groupStage = Stage::where('number', 1)->firstOrFail();
        foreach ($groupStage->games as $game) {
            if ($game->away_team_id !== $winner->id) {
                GameService::setScore($game, 2, 0);// make sure the $winner always wins
            } else {
                GameService::setScore($game, 0, 2);
            }
        }

        foreach (Group::all() as $group) {
            TournamentService::calculateGamesFromGroup($group);
        }

        $stages = Stage::whereNot('number', 1)->orderBy('number')->get();

        foreach ($stages as $stage) {
            foreach ($stage->games as $game) {
                if ($game->away_team_id !== $winner->id) {
                    GameService::setScore($game, 2, 0);// make sure the $winner always wins
                } else {
                    GameService::setScore($game, 0, 2);
                }
            }
        }

        $final = Game::where('match_number', 64)->firstOrFail();
        $final->refresh();

        // test
        $this->assertEquals($final->winner_id, $winner->id);
    }

    private function getGroupScores(): array
    {
        return [
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
    }
}
