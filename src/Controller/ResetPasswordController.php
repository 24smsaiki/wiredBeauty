<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Utils\MailService;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    private $mailer;
    private $verifyEmailHelper;

    public function __construct(
        EntityManagerInterface $entityManager,
        MailService $mailer,
        VerifyEmailHelperInterface $helper

    )
    {
        $this->entityManager = $entityManager;        
        $this->mailer = $mailer;
    }

    #[Route('/reset/password', name: 'reset_password')]
    public function reset(Request $request)
    {   
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if ($user) {

                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();
                $path = "http://".$_SERVER['HTTP_HOST']."/update/password/".$reset_password->getToken();

                $this->mailer->sendMail(
                    $user->getEmail(),
                    "Wired Beauty : Réinitialisation du mot de passe",
                    "Description",
                    " <a href='" . $path ."'>
                    Veuillez cliquer ici pour continuer.
                     </a>"
                );
                
                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail avec la procédure pour réinitialiser votre mot de passe.', array('action' => 'index'), 5);
            
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.', array('action' => 'index'), 5);
            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/update/password/{token}', name: 'update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $encoder) {

        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }

        $now = new \DateTime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveller.');
            return $this->redirectToRoute('reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_password = $form->get('new_password')->getData();
            $password = $encoder->hashPassword($reset_password->getUser(), $new_password);
            $reset_password->getUser()->setPassword($password);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

}
