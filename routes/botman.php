<?php
use App\Http\Controllers\BotManController;
use BotMan\BotMan\Facades\BotMan;

BotMan::hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});
BotMan::hears('Start conversation', BotManController::class . '@startConversation');
