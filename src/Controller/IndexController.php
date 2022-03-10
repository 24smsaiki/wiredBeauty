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

    /**
     * @Route("/our-solution", name="our_solution")
     */
    public function showOurSolution()
    {
        return $this->render('solution/index.html.twig');
    }

    /**
     * @Route("/scientific-validation", name="scientific_validation")
     */
    public function showScientificValidation()
    {
        return $this->render('pages/scientific_validation.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('pages/contact.html.twig');
    }
}