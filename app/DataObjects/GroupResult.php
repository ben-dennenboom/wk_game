<?php

namespace App\DataObjects;

use App\Models\Group;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class GroupResult
{
    public readonly Group $group;

    private Collection $teams;
    private Collection $games;
    private array $result;

    public function __construct(Group $group)
    {
        $this->group = $group;
        $this->games = $group->games;
        $this->teams = $group->teams;

        $this->calculateResult();
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function getFirstTeam(): ?Team
    {
        return $this->getTeam(1);
    }

    public function getSecondTeam(): ?Team
    {
        return $this->getTeam(2);
    }

    public function getTeam($place): ?Team
    {
        $item = array_filter($this->result, function ($item) use ($place) {
            return $item['place'] == $place;
        });

        if (empty($item)) {
            return null;
        }

        return Team::where('id', array_keys($item))->firstOrFail();
    }

    private function calculateResult()
    {
        //  add structure for each team
        $this->result = [];
        foreach ($this->teams as $team) {
            $this->result[$team->id] = [
                'place' => 0,
                'team' => $team->name,
                'played' => 0,
                'won' => 0,
                'lost' => 0,
                'draw' => 0,
                'goals' => 0,
                'goals_against' => 0,
                'goals_diff' => 0,
                'points' => 0,
            ];
        }

        // run all matches and update stats
        foreach ($this->games as $game) {
            // update games played
            $this->result[$game->home_team_id]['played']++;
            $this->result[$game->away_team_id]['played']++;

            $this->result[$game->home_team_id]['goals'] += $game->home_team_score;
            $this->result[$game->home_team_id]['goals_against'] += $game->away_team_score;
            $this->result[$game->home_team_id]['goals_diff'] = $this->result[$game->home_team_id]['goals'] - $this->result[$game->home_team_id]['goals_against'];

            $this->result[$game->away_team_id]['goals'] += $game->away_team_score;
            $this->result[$game->away_team_id]['goals_against'] += $game->home_team_score;
            $this->result[$game->away_team_id]['goals_diff'] = $this->result[$game->away_team_id]['goals'] - $this->result[$game->away_team_id]['goals_against'];


            // set winner
            if ($game->end_in_draw) {
                $this->result[$game->home_team_id]['draw']++;
                $this->result[$game->away_team_id]['draw']++;

                $this->result[$game->home_team_id]['points'] += 1;
                $this->result[$game->away_team_id]['points'] += 1;
            } else {
                $this->result[$game->winner_id]['won']++;

                if ($game->winner_id == $game->home_team_id) {
                    $this->result[$game->away_team_id]['lost']++;
                    $this->result[$game->home_team_id]['points'] += 3;
                } else {
                    $this->result[$game->home_team_id]['lost']++;
                    $this->result[$game->away_team_id]['points'] += 3;
                }
            }
        }

        // order by stats
        uasort($this->result, function ($a, $b) {
            if ($a['points'] === $b['points']) {
                if ($a['goals_diff'] === $b['goals_diff']) {
                    if ($a['goals'] === $b['goals']) {
                        return 0;// Todo: Check onderling duel
                    }
                    return ($a['goals'] > $b['goals']) ? -1 : 1;
                }
                return ($a['goals_diff'] > $b['goals_diff']) ? -1 : 1;
            }

            return ($a['points'] > $b['points']) ? -1 : 1;
        });

        // add place
        $i = 1;
        foreach ($this->result as $key => $item) {
            $this->result[$key]['place'] = $i;
            $i++;
        }
    }
}
