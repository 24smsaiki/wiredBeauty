<?php

namespace App\Controller;

use App\Entity\Skinbiosense;
use App\Entity\User;
use App\Utils\CsvParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use App\Form\FileUploadType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadController extends AbstractController
{
    // ...

    /**
     * @Route("/upload", name="app_upload")
     */
    public function excelCommunesAction(Request $request, SluggerInterface $slugger)
    {
        $form = $this->createForm(FileUploadType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $form['upload_file']->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('csv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', "An error has occurred with the CSV upload.");
                    return $this->redirectToRoute('app_upload');
                }

                return $this->redirectToRoute("import_data_csv", [
                    'file' => $newFilename,
                    'importId' => uniqid()
                ]);
            }
        }
        return $this->render('app/upload_form.html.twig', [
            'form' => $form->createView(),
            'hide_navbar' => true
        ]);
    }
    // ...
}