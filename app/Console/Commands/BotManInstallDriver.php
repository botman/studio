<?php

namespace App\Console\Commands;

use App\BotMan\Helpers\Composer;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class BotManInstallDriver extends Command
{
    const DRIVER_REPOSITORY_URL = 'https://botman.io/studio/drivers.json';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:install-driver {driver}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install available official BotMan drivers.';
    /**
     * @var Composer
     */
    private $composer;
    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new command instance.
     *
     * @param Composer $composer
     * @param Client $client
     */
    public function __construct(Composer $composer, Client $client)
    {
        parent::__construct();

        $this->composer = $composer;
        $this->client = $client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $response = $this->client->get(self::DRIVER_REPOSITORY_URL);
            $drivers = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $this->error('Unable to fetch BotMan driver repository.');
            $this->error('Please check your internet connection ang try again.');
            exit(1);
        }

        $installDriver = $this->argument('driver');

        $driver = collect($drivers)
            ->where('package', 'botman/driver-'.$installDriver)
            ->first();

        if (is_null($driver)) {
            $this->error('Unable to find driver "'.$installDriver.'".');
            exit(1);
        }

        $this->info('Installing driver "'.$driver['name'].'"');

        $this->composer->install('botman/driver-'.$installDriver, function($type, $data) {
            $this->info($data);
        });

        $this->info('Successfully installed driver "'.$driver['name'].'"');
    }
}
