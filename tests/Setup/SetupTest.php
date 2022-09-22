<?php

namespace Tests\Setup;

use App\Models\Game;
use App\Models\Group;
use App\Models\League;
use App\Models\Stage;
use App\Models\Team;
use App\Models\Tournament;
use Tests\TestCase;

class SetupTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_setup()
    {
        $this->assertEquals(32, Team::count());
        $this->assertEquals(8, Group::count());
        $this->assertEquals(64, Game::count());
        $this->assertEquals(5, Stage::count());
        $this->assertEquals(1, Tournament::count());
        $this->assertEquals(1, League::count());
    }
}
