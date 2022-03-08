<?php

namespace App\Controller;

use App\Entity\Skinbiosense;
use App\Entity\User;
use App\Utils\CsvParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;

class ImportDataController extends AbstractController
{

    /**
     * @Route("/import-data-csv/{file}", name="import_data_csv")
     */
    public function importDataCSV(EntityManagerInterface $em, $file)
    {
        $parser = new CsvParser($this->getParameter('csv_directory') . '/' . $file, ',');

        while ($parser->nextRow()) {
            $userRepo = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepo->findBy(['user' => $parser->get('user_id')]);

            if (!empty($user)) {
                $skinBiosense = new Skinbiosense();
                $skinBiosense->setMesure($parser->get('mesure'));
                $skinBiosense->setSessionId($parser->get('session_id'));
                $skinBiosense->setScoreSkinbiosense($parser->get('score_skinbiosense'));
                $skinBiosense->setZoneCode($parser->get('zone_code'));
                $skinBiosense->setProductCode($parser->get('product_code'));
                $skinBiosense->setUser($user[0]);
                $em->persist($skinBiosense);
            }
            $em->flush();
        }

        $this->addFlash('success', "The CSV data was successfully uploaded !");
        return $this->redirectToRoute("app_index");
    }
}