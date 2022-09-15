<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;
    use Uuids;

    protected $fillable = [
        'title',
        'tournament_id',
        'is_general'
    ];
}
