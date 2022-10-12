<?php

namespace App\Services;

use App\DataObjects\GroupResult;
use App\Models\Game;
use App\Models\Group;

class TournamentService
{
    public static function getGroupResults(Group $group): GroupResult
    {
        return new GroupResult($group);
    }

    public static function calculateGamesFromGroup(Group $group)
    {
        $key = substr($group->name, -1);

        $result = self::getGroupResults($group);

        $games = Game::orWhere('get_home_from_slug', 'LIKE', '%'.$key . '%')
            ->orWhere('get_away_from_slug', 'LIKE', '%'.$key . '%')
            ->get();

        foreach ($games as $game) {
            $homeSlug = $game->get_home_from_slug;

            if (!empty($homeSlug) &&
                strlen($homeSlug) == 2 &&
                substr($homeSlug, -1) == $key) {

                $team = $result->getTeam(substr($homeSlug, 0,1));

                if (!empty($team)) {
                    $game->home_team_id = $team->id;
                }
            }

            $awaySlug = $game->get_away_from_slug;

            if (!empty($awaySlug) &&
                strlen($awaySlug) == 2 &&
                substr($awaySlug, -1) == $key) {
                $team = $result->getTeam(substr($awaySlug, 0,1));

                if (!empty($team)) {
                    $game->away_team_id = $team->id;
                }
            }

            $game->save();
        }
    }
}
