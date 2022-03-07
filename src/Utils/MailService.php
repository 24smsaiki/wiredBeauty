<?php

namespace App\Utils;

use Mailjet\Resources;

class MailService
{

    public function sendMail($to, $title, $description, $message)
    {
        $to = $_ENV["APP_ENV"] === "dev" ? $_ENV["DEFAULT_EMAIL"] : $to;

        $mj = new \Mailjet\Client(
            '81829a00726a780981c4ff90b626c0db',
            '0dfea73f743b3bf1f28a6b18dd1e61d2',
            true,
            ['version' => 'v3.1']
        );
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "alsbsb43@gmail.com",
                        'Name' => "Wired Beauty"
                    ],
                    'To' => [
                        [
                            'Email' => $to,
                            'Name' => $to
                        ]
                    ],
                    'Subject' => $title,
                    'TextPart' => $description,
                    'HTMLPart' => $message,
                    'CustomID' => "AppGettingStartedTest"
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
    }
}
