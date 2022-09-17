<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    // add url-slug to sync with
    // icon nullable
    use HasFactory;
    use Uuids;
    protected $fillable = [
        'name',
        'icon',
    ];
}
