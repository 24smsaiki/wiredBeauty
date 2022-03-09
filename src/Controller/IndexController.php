<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="app_index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/studies-and-services", name="studies_and_services")
     */
    public function studiesAndServices()
    {
        return $this->render('pages/studies_services.html.twig');
    }
}