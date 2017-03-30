<?php

namespace App\Console\Commands;

use App\BotMan\Drivers\ConsoleDriver;
use Clue\React\Stdio\Stdio;
use React\EventLoop\Factory;
use Illuminate\Console\Command;
use Mpociot\BotMan\BotManFactory;
use Mpociot\BotMan\Cache\ArrayCache;
use Symfony\Component\EventDispatcher\EventDispatcher;

class BotManTinker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:tinker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tinker around with BotMan.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = app('app');
        $loop = Factory::create();

        $app->singleton('botman', function ($app) use ($loop) {
            $config = config('services.botman', []);
            $botman = BotManFactory::create($config, new ArrayCache());

            $stdio = new Stdio($loop);
            $stdio->getReadline()->setPrompt('You: ');

            $botman->setDriver(new ConsoleDriver($config, $stdio));


            $stdio->on('line', function ($line) use ($botman) {
                $botman->listen();
            });

            return $botman;
        });

        require base_path('routes/botman.php');

        $loop->run();
    }
}
