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

    public function __construct(
        EntityManagerInterface $entityManager,
        MailService $mailer

    )
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    #[Route('/reset/password', name: 'reset_password')]
    public function reset(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
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
                $path = "http://" . $_SERVER['HTTP_HOST'] . "/update/password/" . $reset_password->getToken();

                $this->mailer->sendMail(
                    $user->getEmail(),
                    "Wired Beauty : Password reset",
                    "Description",
                    "Click on <a href='" . $path . "'>this link</a> to reset your password."
                );

                $this->addFlash('success', 'If the email exists, you will receive a mail with a validation link.', array('action' => 'index'), 5);

            } else {
                $this->addFlash('success', 'If the email exists, you will receive a mail with a validation link.', array('action' => 'index'), 5);
            }
        }
        return $this->render('reset_password/index.html.twig', [
            "hide_navbar" => true
        ]);
    }

    #[Route('/update/password/{token}', name: 'update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $encoder)
    {

        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }

        $now = new \DateTime();
        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('danger', 'Your password reset request is expired. Please try again.');
            return $this->redirectToRoute('reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_password = $form->get('new_password')->getData();
            $password = $encoder->hashPassword($reset_password->getUser(), $new_password);
            $reset_password->getUser()->setPassword($password);
            $this->entityManager->flush();

            $this->addFlash('success', 'Your password has been successfully updated.');
            return $this->redirectToRoute('login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
            "hide_navbar" => true
        ]);
    }

}
