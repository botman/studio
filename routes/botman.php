<?php

use App\Http\Controllers\BotManController;

BotMan::hears('Start conversation', BotManController::class.'@startConversation');