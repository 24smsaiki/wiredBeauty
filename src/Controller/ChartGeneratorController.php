<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Knp\Snappy\Pdf;
use Twig\Environment;
use App\Entity\Skinbiosense;
use Symfony\UX\Chartjs\Model\Chart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PdfGenerateController extends AbstractController
{
    
    private $session;
    private $entityManager;

	
    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session

    )
    {
        $this->session = $session;
        $this->entityManager = $entityManager;        
    }
    
    #[Route('/generate', name: 'generate')]
    public function generate()
    {

    }

   
}
