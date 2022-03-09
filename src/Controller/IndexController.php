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

    /**
     * @Route("/blog", name="show_blog")
     */
    public function showBlog()
    {
        return $this->render('blog/index.html.twig');
    }

    /**
     * @Route("/blog/{id}", name="show_specific_blog")
     */
    public function showSpecificBlog($id)
    {
        return $this->render('blog/article.html.twig', [
            "id" => $id
        ]);
    }
}