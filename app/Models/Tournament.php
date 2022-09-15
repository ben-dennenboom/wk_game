<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\DeprecatedUuidMethodsTrait;

class Tournament extends Model
{
    use HasFactory;
    use Uuids;

    protected $fillable = [
        'title',
    ];
}
