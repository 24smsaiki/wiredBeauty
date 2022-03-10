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


class ChartGeneratorController extends AbstractController
{
    
    private $session;
    private $entityManager;

	
    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        ChartBuilderInterface $chartBuilder

    )
    {
        $this->session = $session;
        $this->entityManager = $entityManager; 
        $this->chartBuilder = $chartBuilder;        
    }

    public function search($name)
    {
        $array = [];
        $result = $this->entityManager->createQueryBuilder('s')
            ->select('s.'.$name)
            ->from(Skinbiosense::class, 's')
            ->getQuery()
            ->getArrayResult()
            ;
            foreach($result as $row) {
                        
                array_push($array,$row[$name]); 
            };
        return  $array;
           
           
    }

    public function chart($type,$arrayX,$arrayY){
        $chart = $this->chartBuilder->createChart($type);
            $chart->setData([
                'labels' => $arrayX,
                'datasets' => [
                    [
                        'label' => 'My First dataset',
                        'backgroundColor' => 'rgb(255, 99, 132)',
                        'borderColor' => 'rgb(255, 99, 132)',
                        'data' => $arrayY,
                    ],
                ],
            ]);
            $chart->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                        'suggestedMax' => 1,
                    ],
                ],
            ]);
        return $chart;
    }
    
    #[Route('/generate/{importId}', name: 'rapport')]
    
    public function generate() {
        
        
        $chart1 = $this->chart(Chart::TYPE_LINE,$this->search('productCode'),$this->search('id'));
        $chart2 = $this->chart(Chart::TYPE_BAR,$this->search('productCode'),$this->search('id'));
        $chart3 = $this->chart(Chart::TYPE_RADAR,$this->search('productCode'),$this->search('id'));
        
        return $this->render('pdf_generate/print.html.twig', [
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3 
        ]);
    }

   
}
