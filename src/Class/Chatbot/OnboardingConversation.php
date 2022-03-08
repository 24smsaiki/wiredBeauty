<?php

namespace App\Class\Chatbot;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class OnboardingConversation extends Conversation
{
    protected $firstname;

    protected $email;

    public function askFirstname()
    {
        $this->ask('Hello! What is your firstname?', function(Answer $answer) {
            // Save result
            $this->firstname = $answer->getText();

            $this->say('Nice to meet you '.$this->firstname);
            $this->askNextStep();
        });
    }

    public function askNextStep()
    {
        $this->ask('Shall we proceed? Say YES or NO', [
            [
                'pattern' => 'yes|yep',
                'callback' => function () {
                    $this->say('Okay - we\'ll keep going');
                }
            ],
            [
                'pattern' => 'nah|no|nope',
                'callback' => function () {
                    $this->say('PANIC!! Stop the engines NOW!');
                }
            ]
        ]);
    }

    public function run()
    {
        // This will be called immediately
        $this->askFirstname();
    }
}