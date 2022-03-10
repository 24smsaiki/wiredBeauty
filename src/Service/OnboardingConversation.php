<?php

namespace App\Service;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;


class OnboardingConversation extends Conversation
{
    protected $firstname;

    protected $email;

    public function askFirstname()
    {
        $this->ask('Hello! à qui ai-je a faire ?', function(Answer $answer) {
            // Save result
            $this->firstname = $answer->getText();

            $this->say('Salut '.$this->firstname);
            $this->askNextStep();
        });
    }

    public function askNextStep()
    {
        $this->ask('Souhaitez-vous en savoir plus sur ce que nous faisons ?', [
            [
                'pattern' => 'oui|Oui',
                'callback' => function () {
                    $this->say('Très bien, voici notre brochure de présentation ');
                }
            ],
            [
                'pattern' => 'no|non|Non',
                'callback' => function () {
                    $this->say('Je ne vous embête pas plus longtemps');
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