@props(['game'])
<div class="match d-flex justify-content-center align-items-center my-2" data-game-id="{{ $game->id }}">
    <x-left-label-input input-class="col-2 mx-2" class="justify-content-end" name="home_score" :game="$game" />
    <span class="col-auto mx-1">-</span>
    <x-right-label-input input-class="col-2 mx-2" class="justify-content-start" name="away_score" :game="$game" />
</div>
@pushonce('afterScripts')
    <script src="{{ asset('assets/inputtingObserver.js') }}"></script>
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
@endpushonce
