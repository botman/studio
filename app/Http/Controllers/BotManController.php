<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpociot\BotMan\BotMan;

class BotManController extends Controller
{
	/**
	 * Place your BotMan logic here.
	 */
    public function handle()
    {
    	$botman = app('botman');
        $botman->verifyServices('secret_facebook_verify');

        // Simple respond method
        $botman->hears('Hello', function (BotMan $bot) {
            $bot->reply('Hi there :)');
        });

        // Start a conversation
        $botman->hears('Start conversation', function (BotMan $bot) {
            $bot->startConversation(new ExampleConversation());
        });

        $botman->listen();
    }
}
