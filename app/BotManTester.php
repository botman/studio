<?php

namespace App;

use BotMan\BotMan\BotMan;
use PHPUnit_Framework_TestCase;
use PHPUnit\Framework\Assert as PHPUnit;
use BotMan\BotMan\Drivers\Tests\FakeDriver;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class BotManTester {

    /** @var BotMan */
    private $bot;

    /** @var FakeDriver */
    private $driver;

    /** @var string */
    private $username = 'botman';

    /** @var string */
    private $channel = '#botman';

    public function __construct(BotMan $bot, FakeDriver $driver)
    {
        $this->bot = $bot;
        $this->driver = $driver;
    }

    protected function listen()
    {
        $this->bot->listen();
        $this->driver->isInteractiveMessageReply = false;
    }

    /**
     * @return OutgoingMessage
     */
    protected function getReply()
    {
        $this->listen();
        $messages = $this->getMessages();
        return array_pop($messages);
    }

    public function usingDriver($driver)
    {
        return $this;
    }

    public function receives($message)
    {
        $this->driver->messages = [new IncomingMessage($message, $this->username, $this->channel)];
        return $this;
    }

    public function receivesInteractiveMessage($message)
    {
        $this->driver->isInteractiveMessageReply = true;
        return $this->receives($message);
    }

    public function assertReply($text)
    {
        PHPUnit::assertSame($this->getReply()->getText(), $text);
    }

    public function assertReplyIsNot($text)
    {
        PHPUnit::assertNotSame($this->getReply()->getText(), $text);
    }

    public function assertReplyIn(array $haystack)
    {
        PHPUnit::assertTrue(in_array($this->getReply()->getText(), $haystack));
    }

    public function assertReplyNotIn(array $haystack)
    {
        PHPUnit::assertFalse(in_array($this->getReply()->getText(), $haystack));
    }

    public function assertQuestion($text=null)
    {
        $this->listen();
        $messages = $this->getMessages();

        /** @var Question $question */
        $question = array_pop($messages);
        PHPUnit::assertInstanceOf(Question::class, $question);

        if (!is_null($text)) {
            PHPUnit::assertSame($question->getText(), $text);
        }

        return $this;
    }

    public function getMessages()
    {
        return $this->driver->getBotMessages();
    }

}