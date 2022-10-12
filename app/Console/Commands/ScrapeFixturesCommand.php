<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Group;
use App\Models\Stage;
use App\Models\Team;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScrapeFixturesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wk:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = json_decode(file_get_contents(resource_path('data/wc.json')));

        $teams = [];
        $groups = [];

        $bar = $this->output->createProgressBar(count($data));

        $bar->start();

        /**  6 => {#708
         * +"MatchNumber": 7
         * +"RoundNumber": 1
         * +"DateUtc": "2022-11-22 16:00:00Z"
         * +"Location": "Stadium 974"
         * +"HomeTeam": "Mexico"
         * +"AwayTeam": "Poland"
         * +"Group": "Group C"
         * +"HomeTeamScore": null
         * +"AwayTeamScore": null
         * }**/

        $gameIds = [];
        foreach ($data as $entry) {
            $gameDate = Carbon::createFromFormat('Y-m-d H:i:sZ', $entry->DateUtc);
            $game = new Game(
                [
                    'start' => $gameDate
                ]
            );
            $game->match_number = $entry->MatchNumber;
            $game->slug = 'game_' . $entry->MatchNumber;

            if (!empty($entry->Group)) {
                $game->is_calculated = 0;

                if (empty($groups[$entry->Group])) {
                    $group = Group::updateOrCreate(
                        ['name' => $entry->Group],
                        ['name' => $entry->Group]
                    );
                    $groups[$entry->Group] = $group;
                }
            }
            else {
                $game->is_calculated = 1;
            }

            if (!empty($entry->HomeTeam) &&
                $entry->HomeTeam != 'To be announced' &&
                !is_numeric(substr($entry->HomeTeam, 0, 1)) &&
                strpos($entry->HomeTeam, 'Winners of') === false &&
                strpos($entry->HomeTeam, 'Losers of') === false
            ) {
                if (empty($teams[$entry->HomeTeam])) {
                    $teamHome = Team::updateOrCreate(
                        ['name' => $entry->HomeTeam, 'icon' => $entry->HomeTeam],
                        ['name' => $entry->HomeTeam, 'icon' => $entry->HomeTeam]
                    );

                    if (!empty($entry->Group)) {
                        $teamHome->group_id = $groups[$entry->Group]->id;
                        $teamHome->save();
                    }

                    $teams[$entry->HomeTeam] = $teamHome;
                }
                $game->home_team_id = $teams[$entry->HomeTeam]->id;
            }

            if (is_numeric(substr($entry->HomeTeam, 0, 1))) {
                $game->get_home_from_slug = $entry->HomeTeam;
            }

            if (is_numeric(substr($entry->AwayTeam, 0, 1))) {
                $game->get_away_from_slug = $entry->AwayTeam;
            }

            if (strpos($entry->HomeTeam, 'Winners of') !== false) {
                $refKey = str_replace('Winners of ', '', $entry->HomeTeam);
                $game->home_from_winner_game_id = $gameIds[$refKey];
            }

            if (strpos($entry->AwayTeam, 'Winners of') !== false) {
                $refKey = str_replace('Winners of ', '', $entry->AwayTeam);
                $game->away_from_winner_game_id = $gameIds[$refKey];
            }

            if (strpos($entry->HomeTeam, 'Losers of') !== false) {
                $refKey = str_replace('Losers of ', '', $entry->HomeTeam);
                $game->home_from_loser_game_id = $gameIds[$refKey];
            }

            if (strpos($entry->AwayTeam, 'Losers of') !== false) {
                $refKey = str_replace('Losers of ', '', $entry->AwayTeam);
                $game->away_from_loser_game_id = $gameIds[$refKey];
            }

            if (!empty($entry->AwayTeam) &&
                $entry->AwayTeam != 'To be announced' &&
                !is_numeric(substr($entry->AwayTeam, 0, 1)) &&
                strpos($entry->AwayTeam, 'Winners of') === false &&
                strpos($entry->AwayTeam, 'Losers of') === false) {
                if (empty($teams[$entry->AwayTeam])) {
                    $teamAway = Team::updateOrCreate(
                        ['name' => $entry->AwayTeam, 'icon' => $entry->AwayTeam],
                        ['name' => $entry->AwayTeam, 'icon' => $entry->AwayTeam]
                    );

                    if (!empty($entry->Group)) {
                        $teamAway->group_id = $groups[$entry->Group]->id;
                        $teamAway->save();
                    }

                    $teams[$entry->AwayTeam] = $teamAway;
                }

                $game->away_team_id = $teams[$entry->AwayTeam]->id;
            }

            $game->tournament_id = Tournament::first()->id;
            $number = $entry->RoundNumber;

            if ($number == 2 || $number == 3) {
                $number = 1;
            }
            if($number > 3) {
                $number -= 2;
            }
            $game->stage_id = Stage::where('number', $number)->firstOrFail()->id;

            if(!empty($entry->Group) && !empty($game->awayTeam) &&  !empty($game->homeTeam)){
                $game->group_id = $groups[$entry->Group]->id;
            }

            $game->save();
            $gameIds[$game->match_number] = $game->id;

            $bar->advance();
        }

        return 0;
    }
}
