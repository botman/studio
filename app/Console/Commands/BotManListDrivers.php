<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class BotManListDrivers extends Command
{
    const DRIVER_REPOSITORY_URL = 'http://botman.io.dev/studio/drivers.json';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:list-drivers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available official BotMan drivers.';
    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new command instance.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

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
            $drivers = json_decode($response->getBody());
        } catch (\Exception $e) {
            $this->error('Unable to fetch BotMan driver repository.');
            $this->error('Please check your internet connection ang try again.');
            exit(1);
        }

        $headers = [
            'Name',
            'Service',
            'Description',
        ];

        $tableData = collect($drivers)->transform(function ($driver) {
            return [
                str_replace('botman/driver-', '', $driver->package),
                $driver->name,
                $driver->description,
            ];
        });

        $this->table($headers, $tableData);
    }
}
