<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\StatsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/games', [GameController::class, 'index'])->name('game.index');
Route::get('/leagues', [LeagueController::class, 'index'])->name('league.index');
Route::get('/statistics', [StatsController::class, 'index'])->name('stats.index');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/page1', function () {
    $groups = [
        (object)[
            'id' => 1,
            'name' => 'Group 1'
        ],
        (object)[
            'id' => 2,
            'name' => 'Group 2'
        ],
        (object)[
            'id' => 3,
            'name' => 'Group 3'
        ],
    ];
    $activeGroup = [
        'name' => 'Group 1',
        'teams' => [
            [
                'id' => 1,
                'name' => 'Team 1'
            ],
            [
                'id' => 2,
                'name' => 'Team 2'
            ],
            [
                'id' => 3,
                'name' => 'Team 3'
            ],
            [
                'id' => 4,
                'name' => 'Team 4'
            ],
        ]
    ];

    $games = [
        (object)[
            'id' => 1,
            'home' => 'Team 1',
            'away' => 'Team 2',
        ],
        (object)[
            'id' => 2,
            'home' => 'Team 3',
            'away' => 'Team 4',
        ],
        (object)[
            'id' => 3,
            'home' => 'Team 1',
            'away' => 'Team 3',
        ],
        (object)[
            'id' => 4,
            'home' => 'Team 2',
            'away' => 'Team 4',
        ],
        (object)[
            'id' => 5,
            'home' => 'Team 1',
            'away' => 'Team 4',
        ],
        (object)[
            'id' => 5,
            'home' => 'Team 3',
            'away' => 'Team 2',
        ],
    ];

    return view(
        'page1',
        [
            'groups' => $groups,
            'activeGroup' => json_decode(json_encode($activeGroup), false),
            'games' => $games
        ]
    );
})->name('page1');

Route::get('/page2', function () {
    $rounds =
        [
            (object) [
                'name'=> 'Round 1',
                'games_left'=> [
                    (object)[
                        'id' => 1,
                        'home' => 'Team 1',
                        'away' => 'Team 2',
                    ],
                    (object)[
                        'id' => 2,
                        'home' => 'Team 3',
                        'away' => 'Team 4',
                    ],
                    (object)[
                        'id' => 3,
                        'home' => 'Team 5',
                        'away' => 'Team 6',
                    ],
                    (object)[
                        'id' => 4,
                        'home' => 'Team 7',
                        'away' => 'Team 8',
                    ],
                ],
                'games_right'=> [
                    (object)[
                        'id' => 5,
                        'home' => 'Team 9',
                        'away' => 'Team 10',
                    ],
                    (object)[
                        'id' => 6,
                        'home' => 'Team 11',
                        'away' => 'Team 12',
                    ],
                    (object)[
                        'id' => 7,
                        'home' => 'Team 13',
                        'away' => 'Team 14',
                    ],
                    (object)[
                        'id' => 8,
                        'home' => 'Team 15',
                        'away' => 'Team 16',
                    ],
                ]
            ],

            (object) [
                'name'=> 'Round 2',
                'games_left'=> [
                    (object)[
                        'id' => 9,
                        'home' => 'Team 1',
                        'away' => 'Team 3',
                    ],
                    (object)[
                        'id' => 10,
                        'home' => 'Team 5',
                        'away' => 'Team 7',
                    ],
                ],
                'games_right'=> [
                    (object)[
                        'id' => 11,
                        'home' => 'Team 9',
                        'away' => 'Team 11',
                    ],
                    (object)[
                        'id' => 12,
                        'home' => 'Team 13',
                        'away' => 'Team 15',
                    ],
                ]
            ],

            (object) [
                'name'=> 'Round 3',
                'games_left'=> [
                    (object)[
                        'id' => 13,
                        'home' => 'Team 1',
                        'away' => 'Team 5',
                    ],
                ],
                'games_right'=> [
                    (object)[
                        'id' => 14,
                        'home' => 'Team 9',
                        'away' => 'Team 13',
                    ],
                ]
            ],
            (object) [
                'name'=> 'Round 4',
                'games_center'=> [
                    (object)[
                        'id' => 15,
                        'home' => 'Team 1',
                        'away' => 'Team 9',
                    ],
                ],
            ]
        ];
    return view(
        'page2',
        [
            'rounds' => $rounds
        ]
    );
})->name('page2');
