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
        $rounds = [];
        $games = [];

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


        foreach ($data as $entry) {
            if (!empty($entry->Group)) {
                if (empty($groups[$entry->Group])) {
                    $group = Group::updateOrCreate(
                        ['name' => $entry->Group],
                        ['name' => $entry->Group]
                    );
                    $groups[$entry->Group] = $group;
                }
            }

            $gameDate = Carbon::createFromFormat('Y-m-d H:i:sZ', $entry->DateUtc);
            $game = new Game(
                [
                    'start' => $gameDate
                ]
            );

            if (!empty($entry->HomeTeam)) {
                if (empty($teams[$entry->HomeTeam])) {
                    $teamHome = Team::updateOrCreate(
                        ['name' => $entry->HomeTeam, 'icon' => 'X'],
                        ['name' => $entry->HomeTeam, 'icon' => 'X']
                    );

                    $teams[$entry->HomeTeam] = $teamHome;
                    $game->home_team_id = $teamHome->id;
                }
            }

            if (!empty($entry->AwayTeam)) {
                if (empty($teams[$entry->AwayTeam])) {
                    $teamAway = Team::updateOrCreate(
                        ['name' => $entry->AwayTeam, 'icon' => 'X'],
                        ['name' => $entry->AwayTeam, 'icon' => 'X']
                    );

                    $teams[$entry->AwayTeam] = $teamAway;
                    $game->out_team_id = $teamAway->id;
                }
            }

            if (!empty($game->home_team_id) && !empty($game->out_team_id)) {
                $game->tournament_id = Tournament::first()->id;
                $game->stage_id = Stage::where('name', 'Group')->firstOrFail()->id;
                $game->save();
            }
            $bar->advance();
        }

        return 0;
    }
}
