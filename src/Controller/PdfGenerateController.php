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
    // #[Route('/charts', name: 'charts')]
    // public function index(ChartBuilderInterface $chartBuilder): Response
    // {
    //     $arrayX = [];
    //     $arrayY = [];

    //     $result1 = $this->entityManager->createQueryBuilder('s')
    //     ->select('s.sessionId')
    //     ->from(Skinbiosense::class, 's')
    //     ->getQuery()
    //     ->getArrayResult()
    //     ;
        
    //     foreach($result1 as $row) {
            
    //         array_push($arrayX,$row['sessionId']); 
    //     }

    //     $result = $this->entityManager->createQueryBuilder('s')
    //     ->select('s.mesure')
    //     ->from(Skinbiosense::class, 's')
    //     ->getQuery()
    //     ->getArrayResult()
    //     ;
        
    //     foreach($result as $row) {
            
    //         array_push($arrayY,$row['mesure']); 
    //     }

        // dd($arrayX);
        // dd($arrayY);
        
        // $a= Chart::TYPE_LINE;
        // $chart = $chartBuilder->createChart($a);
        // $chart->setData([
        //     'labels' => ,
        //     'datasets' => [
        //         [
        //             'label' => 'My First dataset',
        //             'backgroundColor' => 'rgb(255, 99, 132)',
        //             'borderColor' => 'rgb(255, 99, 132)',
        //             'data' => $arrayY,
        //         ],
        //     ],
        // ]);

    //     $chart->setOptions([
    //         'scales' => [
    //             'y' => [
    //                 'suggestedMin' => 0,
    //                 'suggestedMax' => 1,
    //             ],
    //         ],
    //     ]);

    //     return $this->render('pdf_generate/stat.html.twig', [
    //         'chart' => $chart,
    //     ]);
    // }

    #[Route('/generate-charts', name: 'generate_charts')]
    public function createcharts(Request $request,ChartBuilderInterface $chartBuilder)
    {
       
        //récuperer la liste des champs dans la base skinbiosense
        $schemaManager = $this->entityManager->getConnection()->getSchemaManager();
        $sessionCharts = $this->session->get('sessionCharts',[]);
        $columns = $schemaManager->listTableColumns('skinbiosense');

        
        $columnNames = [];
        foreach($columns as $column){
            $columnNames[] = $column->getName();
        }
        
        $form = $this->createFormBuilder()
        ->add('x', ChoiceType::class, [
            'label' => "Choisissez votre x",
            'choices'  => array_flip($columnNames),
            
        ])
        ->add('y', ChoiceType::class, [
            'label' => "Choisissez votre y",
            'choices'  => array_flip($columnNames),
            
        ])

        ->add('chartType', ChoiceType::class, [
            'label' => "Choisissez votre graphique",
            'choices'  => [
                "pie" => "pie",
                "bar" => "bar"
            ],
            
        ])

        ->add('booking', SubmitType::class, [
            'label' => "Je genere mon chart",
            'attr' => ['class' => 'primary-btn pc-btn btn-block mt-3'],
        ])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $x = $form['x']->getData();
            $y = $form['y']->getData();
            $arrayX = [];
            $arrayY = [];
         
            switch ($x) {
                case ($x === 0):
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.id')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayX,$row['id']); 
                    }
                break;
                case ($x === 1):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.userId')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayX,$row['userId']); 
                    }
                break;
                case ($x === 2):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.productCode')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayX,$row['productCode']); 
                    }
                   
                break;
                case ($x === 3):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.zoneCode')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayX,$row['zoneCode']); 
                    }
                break;
                case ($x === 4):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.scoreSkinbiosense')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayX,$row['scoreSkinbiosense']); 
                    }
                break;
                case ($x === 5):
                    dd('here');
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.sessionId')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayX,$row['sessionId']); 
                    }
                break;
                case ($x === 6):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.mesure')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayX,$row['mesure']); 
                    }
                break;
            }

            switch ($y) {
                case ($y === 0):
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.id')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayY,$row['id']); 
                    }
                break;
                case ($y === 1):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.userId')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayY,$row['userId']); 
                    }
                break;
                case ($y === 2):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.productCode')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayY,$row['productCode']); 
                    }
                   
                break;
                case ($y === 3):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.zoneCode')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayY,$row['zoneCode']); 
                    }
                break;
                case ($y === 4):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.scoreSkinbiosense')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayY,$row['scoreSkinbiosense']); 
                    }
                break;
                case ($y === 5):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.sessionId')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayY,$row['sessionId']); 
                    }
                break;
                case ($y === 6):
                    
                    $result = $this->entityManager->createQueryBuilder('s')
                    ->select('s.mesure')
                    ->from(Skinbiosense::class, 's')
                    ->getQuery()
                    ->getArrayResult()
                    ;
                    
                    foreach($result as $row) {
                        
                        array_push($arrayY,$row['mesure']); 
                    }
                break;
            }
            
            // dd($arrayX)
       
            // dd($arrayY);
            // $a = Chart::TYPE_LINE;
            $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

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

            
            
           
            $this->session->set('sessionCharts',$chart);

            return $this->redirectToRoute('print',[
                // dd($sessionCharts),
                'sessionCharts' => $sessionCharts,
                
            ]);
            
        }

        return $this->render('pdf_generate/form.html.twig',[
            'form' => $form->createView(),  

        ]);


    }

    #[Route('/print', name: 'print')]
    public function hell(){
        $sessionCharts = $this->session->get('sessionCharts',[]);

        return $this->render('pdf_generate/print.html.twig',[
            'sessionCharts' => $sessionCharts,
        ]);
        
    }
}
