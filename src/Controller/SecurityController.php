<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Utils\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class SecurityController extends AbstractController
{

    private $verifyEmailHelper;
    private $entityManager;

    public function __construct(
        VerifyEmailHelperInterface $helper,
        EntityManagerInterface $entityManager
    ) {
        $this->verifyEmailHelper = $helper;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/verify", name="registration_confirmation_route")
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');
        if (null === $id) {
            return $this->redirectToRoute('app_index');
        }

        $user = $userRepository->find($id);
        if (null === $user) {
            return $this->redirectToRoute('app_index');
        }

        if ($user->getIsVerified()) {
            $this->addFlash("success", "Your account is already activated. You can sign in at any time.");
            return $this->redirectToRoute('login');
        }

        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        $user->setIsVerified(true);
        $this->entityManager->flush();

        $this->addFlash('success', 'Your account is successfully activated. You can now sign in.');
        return $this->redirectToRoute('app_index');
    }

}