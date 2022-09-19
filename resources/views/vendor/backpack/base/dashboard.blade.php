@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
    'type'        => 'progress',
    'class'       => 'card text-white bg-primary mb-2',
    'value'       => \App\Models\Game::where('finished', 1)->count(),
    'description' => 'Games played',
    'progress'    => \App\Models\Game::where('finished', 1)->count() / \App\Models\Game::count() * 100, // integer
    ];

    $widgets['before_content'][] = [
    'type'        => 'progress',
    'class'       => 'card text-white bg-primary mb-2',
    'value'       => \App\Models\User::count(),
    'description' => 'First 100 users',
    'progress'    => \App\Models\User::count(), // integer
    ];

    $widgets['before_content'][] = [
    'type'        => 'progress',
    'class'       => 'card text-white bg-primary mb-2',
    'value'       => \App\Models\League::count(),
    'description' => 'First 20 leagues',
    'progress'    => \App\Models\League::count(), // integer
    ];
@endphp

@section('content')
@endsection
