<div class="game_small" id="game_{{$game->id}}">
    <div class="row" id="winner-row" @if(!empty($game->getUserPrediction($user->id)))data-winner="{{$game->getUserPrediction($user->id)->winner}}"@endif>
        <div class="col-md-6 game_home">
            <input type="number" class="prediction_submit" value='{{ getHomePrediction($predictions, $game) }}'
                   onchange="predictor.saveGame(this, '{{ $game->slug }}', '{{($game->type == \App\Structs\Game::KNOCKOUT)}}')"/>

            @if(!empty($game->getHomeTeam($user)))
                @include('elements.team_flag', ['team' => $game->getHomeTeam($user)])
            @endif

            <div class="game_name">
                <span>
                    @if(!empty($game->getHomeTeam($user)))
                        {{$game->getHomeTeam($user)->name}}
                    @endif
                </span>
                @if(!empty($game->home_name))<span class="game_team_name">{{$game->home_name}}</span>@endif
            </div>
        </div>
        <div class="col-md-6 game_out">
            <input type="number" class="prediction_submit" value='{{ getOutPrediction($predictions, $game) }}'
                   onchange="predictor.saveGame(this, '{{ $game->slug }}', '{{($game->type == \App\Structs\Game::KNOCKOUT)}}')"/>

            @if(!empty($game->getOutTeam($user)))
                @include('elements.team_flag', ['team' => $game->getOutTeam($user)])
            @endif

            <div class="game_name_out game_name">
                <span>
                    @if(!empty($game->getOutTeam($user)))
                        {{$game->getOutTeam($user)->name}}
                    @endif
                </span>
                @if(!empty($game->out_name))<span class="game_team_name_out">{{$game->out_name}}</span>@endif
            </div>
        </div>
        <div class="select_winner @if($game->type == \App\Structs\Game::GROUP || strlen($game->getUserPredictionScore($user->id)[0]) == 0 || $game->getUserPredictionScore($user->id)[0] != $game->getUserPredictionScore($user->id)[1]) hidden @endif">
            <span><img data-position="home" data-game-slug="{{$game->slug}}" src="@if(!empty($game->getUserPrediction($user->id)) && $game->getUserPrediction($user->id)->winner === 0){{asset('img/arrow_blue.svg')}}@else{{asset('img/arrow_grey.svg')}}@endif" class="select_winner_arrow"/></span>
            Winner
            <span><img data-position="out" data-game-slug="{{$game->slug}}"  src="@if(!empty($game->getUserPrediction($user->id)) && $game->getUserPrediction($user->id)->winner === 1){{asset('img/arrow_blue.svg')}}@else{{asset('img/arrow_grey.svg')}}@endif" class="select_winner_arrow"/></span>
        </div>
    </div>
</div>
