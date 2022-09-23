<?php

namespace App\Services;

use App\Models\Game;

class GameService
{
    public static function setScore(
        Game $game,
        int $homeScore,
        int $awayScore,
        int $homeScorePenalties = null,
        int $awayScorePenalties = null
    ): Game {
        $game->home_team_score = $homeScore;
        $game->away_team_score = $awayScore;

        if ($homeScore > $awayScore) {
            $game->winner_id = $game->home_team_id;
        }
        if ($homeScore < $awayScore) {
            $game->winner_id = $game->away_team_id;
        }

        if ($homeScore === $awayScore) {
            if (empty($homeScorePenalties) && empty($awayScorePenalties)) {
                $game->end_in_draw = 1;
            } else {
                if ($homeScorePenalties > $awayScorePenalties) {
                    $game->winner_id = $game->home_team_id;
                }
                if ($homeScorePenalties < $awayScorePenalties) {
                    $game->winner_id = $game->away_team_id;
                }
            }
        }

        $game->finished = 1;
        $game->save();

        return $game;
    }
}
