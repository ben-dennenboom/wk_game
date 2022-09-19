<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;
    use Uuids;
    use CrudTrait;

    protected $fillable = ['name', 'tournament_id'];
}
