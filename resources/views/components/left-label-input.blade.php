@props(['name', 'game', 'value' => '', 'denomination' => 'home', 'inputClass' => 'col-1', 'labelClass' => 'col-auto'])
<div {{ $attributes->merge(['class' => 'd-flex align-items-center']) }}>
    <div class="{{ $labelClass }}"><span>{{ $game->$denomination }}</span></div>
    <div class="{{ $inputClass }}"><input class="form-control score bg-light" type="text" name="{{ $name }}" value="{{ $value }}" /></div>
</div>

