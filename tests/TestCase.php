<?php

namespace Tests;

use App\Console\Commands\ScrapeFixturesCommand;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('wk:setup');
    }
}
