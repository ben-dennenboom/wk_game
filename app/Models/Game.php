<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;
    use Uuids;
    use CrudTrait;

    protected $fillable = [
        'start',
        'home_team_id',
        'away_team_id',
        'start',
        'tournament_id',
        'stage_id',
        'is_calculated',

        'home_from_winner_game_id',
        'away_from_winner_game_id',
        'home_from_loser_game_id',
        'away_from_loser_game_id',

        'get_home_from_slug',
        'get_away_from_slug',
    ];

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function loser(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
