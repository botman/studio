<?php

namespace Tests;

use BotMan\BotMan\BotMan;
use BotMan\Studio\Testing\BotManTester;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var BotMan
     */
    protected $botman;

    /**
     * @var BotManTester
     */
    protected $bot;
}
