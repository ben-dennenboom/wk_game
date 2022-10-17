@props(['rounds'])
<div class="d-flex">
    @foreach(array_slice($rounds,0,-1) as $round)
        <div class="d-flex flex-column">
            <div class="d-flex flex-column flex-fill justify-content-center">
                @foreach($round->games_left as $game)
                    <div class="match d-flex flex-column" data-game-id="{{ $game->id }}">
                        <x-left-label-input label-class="mx-1" input-class="col-4 m-2" denomination="home" :game="$game" name="home_score"/>
                        <x-left-label-input label-class="mx-1" input-class="col-4 m-2" denomination="away" :game="$game" name="away_score"/>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    @php
        $finals = array_pop($rounds)
    @endphp

    <div class="d-flex flex-column justify-content-center">
        @foreach($finals->games_center as $game)
            <div>
                <div class="match d-flex justify-content-evenly align-items-center my-2" data-game-id="{{ $game->id }}">
                    <x-left-label-input class="justify-content-end" input-class="col-4 m-2" name="home_score" :game="$game" />
                    <span class="col-auto">-</span>
                    <x-right-label-input input-class="col-4 m-2" name="away_score" :game="$game" />
                </div>
            </div>
        @endforeach
    </div>

    @foreach(array_reverse($rounds) as $round)
        <div class="d-flex flex-column">
            <div class="d-flex flex-column justify-content-center flex-fill">
                @foreach($round->games_right as $game)
                    <div class="match d-flex flex-column" data-game-id="{{ $game->id }}">
                        <x-right-label-input class="justify-content-start" label-class="mx-1" input-class="col-4 m-2" denomination="home" :game="$game" name="home_score"/>
                        <x-right-label-input class="justify-content-start" label-class="mx-1" input-class="col-4 m-2" denomination="away" :game="$game" name="away_score"/>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@push('afterScripts')
    <script>
        (function() {
            document.querySelectorAll('.match').forEach(function(match) {
                match.querySelectorAll('.score').forEach(function(score) {
                    score.addEventListener('change', function(e) {
                        //Remove these two lines if you don't want to fill the blanks inputs with zeros
                        match.querySelector('input[name="home_score"]').value === '' ? match.querySelector('input[name="home_score"]').value = 0 : null;
                        match.querySelector('input[name="away_score"]').value === '' ? match.querySelector('input[name="away_score"]').value = 0 : null;

                        console.log({
                            gameId: match.dataset.gameId,
                            home: match.querySelector('input[name="home_score"]').value,
                            away: match.querySelector('input[name="away_score"]').value
                        });
                    });
                });
            });
        })();
    </script>

@endpush
