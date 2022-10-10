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
            $game->loser_id = $game->away_team_id;
        }
        if ($homeScore < $awayScore) {
            $game->winner_id = $game->away_team_id;
            $game->loser_id = $game->home_team_id;
        }

        if ($homeScore === $awayScore) {
            if (empty($homeScorePenalties) && empty($awayScorePenalties)) {
                $game->end_in_draw = 1;
            } else {
                if ($homeScorePenalties > $awayScorePenalties) {
                    $game->winner_id = $game->home_team_id;
                    $game->loser_id = $game->away_team_id;
                }
                if ($homeScorePenalties < $awayScorePenalties) {
                    $game->winner_id = $game->away_team_id;
                    $game->loser_id = $game->home_team_id;
                }
            }
        }

        $game->finished = 1;
        $game->save();

        // calculate related games
        $game = self::calculateGame($game);

        return $game;
    }

    public static function calculateGame(Game $sourceGame): Game
    {
        if ($sourceGame->finished != 1) {
            return $sourceGame;
        }
        if (empty($sourceGame->winner_id != 1)) {
            return $sourceGame;
        }

        $games = Game::orWhere('home_from_winner_game_id', $sourceGame->id)
            ->orWhere('away_from_winner_game_id', $sourceGame->id)
            ->orWhere('home_from_loser_game_id', $sourceGame->id)
            ->orWhere('away_from_loser_game_id', $sourceGame->id)
            ->get();

        foreach ($games as $game) {
            if ($game->home_from_winner_game_id == $sourceGame->id) {
                $game->home_team_id = $sourceGame->winner_id;
            }
            if ($game->away_from_winner_game_id == $sourceGame->id) {
                $game->away_team_id = $sourceGame->winner_id;
            }
            if ($game->home_from_loser_game_id == $sourceGame->id) {
                $game->home_team_id = $sourceGame->loser_id;
            }
            if ($game->away_from_loser_game_id == $sourceGame->id) {
                $game->away_team_id = $sourceGame->loser_id;
            }

            $game->save();
        }

        return $sourceGame;
    }
}
