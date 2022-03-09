<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Utils\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{

    private $mailer;
    private $verifyEmailHelper;

    public function __construct(
        MailService $mailer,
        VerifyEmailHelperInterface $helper
    )
    {
        $this->mailer = $mailer;
        $this->verifyEmailHelper = $helper;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setIsVerified(false);
            $user->setRoles(["ROLE_USER"]);

            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi de mail pour valider son compte
            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'registration_confirmation_route',
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()]
            );

            $this->mailer->sendMail(
                $user->getEmail(),
                "Wired Beauty : Last step to activate your account",
                "Description",
                " To activate your account please <a href='" . $signatureComponents->getSignedUrl() ."'>
                                 click here.
                          </a>"
            );
            $this->addFlash('success', "Account created ! You need to activate it by clicking the link you received in your mail.");

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            "hide_navbar" => true
        ]);
    }
}
