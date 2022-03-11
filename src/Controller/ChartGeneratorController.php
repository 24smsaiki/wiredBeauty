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
    
    private $entityManager;

	
    public function __construct(
        EntityManagerInterface $entityManager,
        ChartBuilderInterface $chartBuilder

    )
    {
        $this->entityManager = $entityManager; 
        $this->chartBuilder = $chartBuilder;        
    }

    public function search($name,$nameWhere,$value)
    {
        $array = [];
        $result = $this->entityManager->createQueryBuilder('s')
            ->select('s.'.$name)
            ->from(Skinbiosense::class, 's')
            ->where('s.'.$nameWhere.'='.$value)
            ->getQuery()
            ->getArrayResult()
            ;
            foreach($result as $row) {
                        
                array_push($array,$row[$name]); 
            };
        
            return $array;
        
    }

    public function average($name)
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
        
            return $array;
        
    }

    public function chart($type,$arrayX,$arrayY,$color){
        $chart = $this->chartBuilder->createChart($type);
            $chart->setData([
                'labels' => $arrayX,
                'datasets' => [
                    [
                        'label' => 'My First dataset',
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'data' => $arrayY,
                    ],
                ],
            ]);
            $chart->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                        'suggestedMax' => 0,2,                          
                    ],
                ],
            ]);
        return $chart;
    }


    public function multiAxis($type,$arrayX,$arrayY,$arrayZ,$arrayZa){
        $chart = $this->chartBuilder->createChart($type);
            $chart->setData([
                'labels' => $arrayX,
                'datasets' => [
                    [
                        'label' => '',
                        'backgroundColor' => 'rgb(255, 99, 132)',
                        'borderColor' => 'rgb(255, 99, 132)',
                        'data' => $arrayY,
                        
                    ],
                    [
                        'label' => '',
                        'backgroundColor' => 'rgb(255, 227, 53)',
                        'borderColor' => 'rgb(255, 227, 53)',
                        'data' => $arrayZ,
                    ],
                    [
                        'label' => '',
                        'backgroundColor' => 'rgb(53, 199, 255)',
                        'borderColor' => 'rgb(53, 199, 255)',
                        'data' => $arrayZa,
                    ],
                    
                ],
            ]);
            $chart->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                        'suggestedMax' => 0,2, 
                    ],
                ],
            ]);

        return $chart;
    }
    
    #[Route('/generate/{importId}', name: 'rapport')]
    
    public function generate($importId) {
        $chart = $this->entityManager->getRepository(Skinbiosense::class)->findByImportId($importId);
        if ( $chart[0]->getUser() == $this->getUser() ) {
        
            $scoreAverageArray = $this->average('scoreSkinbiosense');
            $average = array_sum($scoreAverageArray) / count($scoreAverageArray);
            #731459
#D3D326
#398A98
#26D389
#7A86A1
#B87F76
            
            $chart1 = $this->chart(Chart::TYPE_LINE,$this->search('id','scoreSkinbiosense',1),$this->search('mesure','scoreSkinbiosense',1), '#731459');
            $chart2 = $this->chart(Chart::TYPE_BAR,$this->search('id','scoreSkinbiosense',1),$this->search('zoneCode','scoreSkinbiosense',1), '#D3D326');  
            
            $chart3 = $this->chart(Chart::TYPE_LINE,$this->search('id','scoreSkinbiosense',2),$this->search('mesure','scoreSkinbiosense',2),'#398A98');
            $chart4 = $this->chart(Chart::TYPE_BAR,$this->search('id','scoreSkinbiosense',2),$this->search('zoneCode','scoreSkinbiosense',2),'#26D389');
            
            $chart5 = $this->chart(Chart::TYPE_LINE,$this->search('id','scoreSkinbiosense',3),$this->search('mesure','scoreSkinbiosense',3),'#7A86A1');
            $chart6 = $this->chart(Chart::TYPE_BAR,$this->search('id','scoreSkinbiosense',3),$this->search('zoneCode','scoreSkinbiosense',3),'#B87F76');
            
            $chart7 = $this->multiAxis(Chart::TYPE_LINE,$chart,$this->search('mesure','scoreSkinbiosense',1),$this->search('mesure','scoreSkinbiosense',2),$this->search('mesure','scoreSkinbiosense',3));
        
        } else {
            return $this->redirectToRoute('login');
        }

        return $this->render('pdf_generate/print.html.twig', [
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'chart4' => $chart4,
            'chart5' => $chart5,
            'chart6' => $chart6,
            'chart7' => $chart7,
            'average' => $average 
        ]);
    }

    #[Route('/chart/history', name: 'history')]
    
    public function history() {
        $imports = $this->entityManager->getRepository(Skinbiosense::class)->findByUser($this->getUser());
        $myCharts = [];

        foreach ($imports as $import) {
            array_push($myCharts,$import->getImportId());
        }

        $myCharts = array_unique($myCharts);
    
        return $this->render('charts_history/index.html.twig', [
            "myCharts" => $myCharts,
            "hide_navbar" => true
        ]);
    }

   
}
