<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Service\TokenGenerator;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/register", name="app_register")
 */
class RegistrationController extends AbstractController
{
    private EntityManagerInterface $manager;
    private Mailer $mailer;

    public function __construct(EntityManagerInterface $manager, Mailer $mailer)
    {
        $this->entityManager = $manager;
        $this->mailer = $mailer;
    }

    /**
     * @Route("", name="")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenGenerator $tokenGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setIsVerified(false);
            $user->setConfirmationToken($tokenGenerator->generate());
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // do anything else you need here, like send an email
            $email = $this->mailer->buildEmail(
                "Tournament | Confirmation de compte",
                $user->getEmail(),
                'emails/register.html.twig',
                ['user' => $user]
            );
            $this->mailer->send($email);

            $this->addFlash(
                "success",
                "Un message avec un lien de confirmation vous a été envoyé par mail. Veuillez suivre ce lien pour activer votre compte."
            );
            
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check/{id<\d+>}", name="_check")
     */
    public function check(User $user, Request $request): RedirectResponse
    {
        $token = $request->get('token');
        if (empty($token) || $token !== $user->getConfirmationToken()) {
            $this->addFlash(
                'danger',
                'Votre token n\'est pas valide'
            );
            return $this->redirectToRoute('app_login');
        }

        if ($user->getCreatedAt() < new \DateTime(User::TOKEN_VALIDITY)) {
            $this->addFlash(
                'danger',
                'Votre token est expiré'
            );
            return $this->redirectToRoute('app_login');
        }

        $user->setConfirmationToken(null)
            ->setIsVerified(true);

        $this->entityManager->flush();

        $this->addFlash(
            'success',
            'Votre compte a été validé'
        );

        return $this->redirectToRoute('app_login');
    }
}
