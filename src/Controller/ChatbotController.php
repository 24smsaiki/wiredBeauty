<?php

namespace App\Controller;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatbotController extends AbstractController
{
    #[Route('/chatbot', name: 'chatbot')]
    public function index(Request $request) {

        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        $config = [];

        $botman = BotManFactory::create($config);

        $botman->hears('(hello|hi|hey)', function (BotMan $bot) {
            $bot->reply('Hello!');
        });

        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Sorry, I did not understand.');
        });

        $botman->listen();

        return new Response();

    }
    
    #[Route('/chatframe', name: 'chatframe')]
    public function chatframeAction(Request $request)
    {
        return $this->render('chatbot/chat_frame.html.twig');
    }


}
