@foreach($games as $game)
    <x-score-input :game="$game" />
@endforeach
