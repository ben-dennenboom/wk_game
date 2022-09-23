<?php

namespace Tests\Unit;

use App\Models\Game;
use App\Services\GameService;
use Tests\TestCase;

class GameFlowTest extends TestCase
{
    public function test_game_results_home_wins()
    {
        // Home wins
        $game = GameService::setScore(
            Game::firstOrFail(),
            5,
            0
        );

        $this->assertEquals($game->home_team_id, $game->winner_id);
        $this->assertFalse((bool)$game->end_in_draw);
    }

    public function test_game_results_draw_no_wins()
    {
        // Draw, no winner
        $game = GameService::setScore(
            Game::firstOrFail(),
            5,
            5
        );

        $this->assertEquals(null, $game->winner_id);
        $this->assertTrue((bool)$game->end_in_draw);
    }

    public function test_game_results_draw_home_wins()
    {
        // Draw, home wins
        $game = GameService::setScore(
            Game::firstOrFail(),
            5,
            5,
            5,
            0
        );

        $this->assertEquals($game->home_team_id, $game->winner_id);
        $this->assertFalse((bool)$game->end_in_draw);
    }

    public function test_game_results_draw_away_wins()
    {
        // Draw, away wins
        $game = GameService::setScore(
            Game::firstOrFail(),
            5,
            5,
            0,
            5
        );

        $this->assertEquals($game->away_team_id, $game->winner_id);
        $this->assertFalse((bool)$game->end_in_draw);
    }

    public function test_game_results_away_wins()
    {
        // Away wins
        $game = GameService::setScore(
            Game::firstOrFail(),
            0,
            5
        );

        $this->assertEquals($game->away_team_id, $game->winner_id);
        $this->assertFalse((bool)$game->end_in_draw);
    }
}
