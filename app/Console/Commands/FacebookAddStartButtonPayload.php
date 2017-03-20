<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mpociot\BotMan\Http\Curl;

class FacebookAddStartButtonPayload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'botman:facebookAddStartButton {payload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a Facebook Get Started button with a payload';

    /**
     * @var Curl
     */
    private $http;

    /**
     * Create a new command instance.
     *
     * @param Curl $http
     */
    public function __construct(Curl $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $payload = $this->argument('payload');

        $response = $this->http->post('https://graph.facebook.com/v2.6/me/messenger_profile?access_token='.env('FACEBOOK_TOKEN'), [], [
                "get_started" => [
                    "payload" => $payload,
                ],
            ]);

        $responseObject = json_decode($response->getContent());

        if ($response->getStatusCode() == 200) {
            $this->info('Get Started payload was set to: '.$payload);
        } else {
            $this->error('Something went wrong: '.$responseObject->error->message);
        }
    }
}
