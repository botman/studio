<?php

namespace App\BotMan\Drivers;

use Clue\React\Stdio\Stdio;
use Slack\File;
use Mpociot\BotMan\User;
use Mpociot\BotMan\Answer;
use Mpociot\BotMan\Message;
use Mpociot\BotMan\Question;
use Illuminate\Support\Collection;
use Mpociot\BotMan\Interfaces\DriverInterface;
use Mpociot\BotMan\Messages\Message as IncomingMessage;

class ConsoleDriver implements DriverInterface
{
    /** @var string */
    protected $message;

    /** @var Stdio */
    protected $client;

    /** @var string */
    protected $bot_id;

    /** @var boolean */
    protected $hasQuestion = false;

    /** @var array */
    protected $lastQuestions;

    const DRIVER_NAME = 'SlackRTM';

    const BOT_NAME = 'BotMan';

    /**
     * Driver constructor.
     * @param array $config
     * @param Stdio $client
     */
    public function __construct(array $config, Stdio $client)
    {
        $this->event = Collection::make();
        $this->config = Collection::make($config);
        $this->client = $client;

        $this->client->on('line', function ($line) {
            $this->message = $line;
        });
    }

    /**
     * Return the driver name.
     *
     * @return string
     */
    public function getName()
    {
        return self::DRIVER_NAME;
    }

    /**
     * Determine if the request is for this driver.
     *
     * @return bool
     */
    public function matchesRequest()
    {
        return false;
    }

    /**
     * @param  Message $message
     * @return Answer
     */
    public function getConversationAnswer(Message $message)
    {
        $index = (int)$message->getMessage() - 1;

        if ($this->hasQuestion && isset($this->lastQuestions[$index])) {
            $question = $this->lastQuestions[$index];
            return Answer::create($question['name'])
                ->setInteractiveReply(true)
                ->setValue($question['value'])
                ->setMessage($message);
        }
        return Answer::create($this->message)->setMessage($message);
    }

    /**
     * Retrieve the chat message.
     *
     * @return array
     */
    public function getMessages()
    {
        return [new Message($this->message, 999, '#channel', $this->message)];
    }

    /**
     * @return bool
     */
    public function isBot()
    {
        return strpos($this->message, 'BotMan: ') === 0;
    }

    /**
     * @param string|Question|IncomingMessage $message
     * @param Message $matchingMessage
     * @param array $additionalParameters
     * @return $this
     */
    public function reply($message, $matchingMessage, $additionalParameters = [])
    {
        $questionData = null;
        if ($message instanceof IncomingMessage) {
            $text = $message->getMessage();
        } elseif ($message instanceof Question) {
            $text = $message->getText();
            $questionData = $message->toArray();
        } else {
            $text = $message;
        }

        $this->client->writeln(self::BOT_NAME.': '.$text);

        if (!is_null($questionData)) {
            foreach ($questionData['actions'] as $key => $action) {
                $this->client->writeln(($key+1).') '.$action['text']);
            }
            $this->hasQuestion = true;
            $this->lastQuestions = $questionData['actions'];
        }

        return $this;
    }

    /**
     * Send a typing indicator.
     * @param Message $matchingMessage
     * @return mixed
     */
    public function types(Message $matchingMessage)
    {
        $this->client->writeln(self::BOT_NAME.': ...');
    }

    /**
     * Retrieve User information.
     * @param Message $matchingMessage
     * @return User
     */
    public function getUser(Message $matchingMessage)
    {
        return new User($matchingMessage->getUser());
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        return false;
    }
}
