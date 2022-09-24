<?php

namespace App\Services;

use App\DataObjects\GroupResult;

class TournamentService
{
    public static function getGroupResults(Group $group): GroupResult
    {
        $result = GroupResult::startFromGroup($group);

        return $result;
    }
}
