<?php

namespace App\Models;

use App\Models\Traits\Uuids;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    use HasFactory;
    use Uuids;
    use CrudTrait;

    protected $fillable = ['name', 'tournament_id'];

    public function games():HasMany {
        return $this->hasMany(Game::class);
    }
}
