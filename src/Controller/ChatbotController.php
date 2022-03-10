<?php

namespace App\Controller;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\SymfonyCache;
use App\Service\OnboardingConversation;
use BotMan\BotMan\Drivers\DriverManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatbotController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    function messageAction()
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        $config = [];

        $adapter = new FilesystemAdapter();
        $botman = BotManFactory::create($config, new SymfonyCache($adapter));
        
        $botman->hears('bonjour', function($bot) {
            $bot->startConversation(new OnboardingConversation);
        });
        
        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Sorry, I did not understand.');
        });

        $botman->listen();

        return new Response();


        
    }
    /**
     * @Route("/chatframe", name="chatframe")
     */
    public function chatframeAction()
    {
        return $this->render('chatbot/chat_frame.html.twig');
    }

}
