<?php

namespace Tests;

use Illuminate\Support\Facades\Hash;
use BotMan\Studio\Testing\BotManTester;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Contracts\Console\Kernel;
use BotMan\BotMan\Drivers\Tests\FakeDriver;
use BotMan\BotMan\Drivers\Tests\ProxyDriver;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        DriverManager::loadDriver(ProxyDriver::class);
        $fakeDriver = new FakeDriver();
        ProxyDriver::setInstance($fakeDriver);

        $app->make(Kernel::class)->bootstrap();

        $this->botman = $app->make('botman');
        $this->bot = new BotManTester($this->botman, $fakeDriver, $this);

        Hash::driver('bcrypt')->setRounds(4);

        return $app;
    }
}
