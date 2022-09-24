<?php

namespace App\DataObjects;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;

class GroupResult
{
    public readonly Group $group;

    private Collection $teams;
    private Collection $games;
    private array $result;

    public function __construct(Group $group)
    {
        $this->group = $group;
        $this->games = $group->games;
        $this->teams = $group->teams;

        $this->calculateResult();
    }

    public function getResult(): array
    {
        return $this->result;
    }

    private function calculateResult()
    {
        $this->result = [];
    }
}
