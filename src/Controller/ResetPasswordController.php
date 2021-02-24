<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Service\TokenGenerator;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/reset-password", name="app_reset_password")
 */
class ResetPasswordController extends AbstractController
{
    private EntityManagerInterface $manager;
    private Mailer $mailer;

    public function __construct(EntityManagerInterface $manager, Mailer $mailer)
    {
        $this->manager = $manager;
        $this->mailer = $mailer;
    }
    
    /**
     * @Route("", name="")
     *
     * @return RedirectResponse|Response
     */
    public function reset(Request $request, TokenGenerator $tokenGenerator)
    {
        // Si pas d'email reçu, demande à l'utilisateur
        if (!$email = $request->get('email')) {
            return $this->render('security/reset_password_request.html.twig');
        }
        // On récupère l'utilisateur avec le mail reçu
        $user = $this->manager->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);
        // S'il existe
        if (null !== $user) {
            // On génère un token
            $user->setResetPasswordToken($tokenGenerator->generate());
            $this->manager->flush();

            
            // On genvoie un mail
            $email = $this->mailer->buildEmail(
                "Tournament | Reset de mot de passe",
                $user->getEmail(),
                'emails/reset_password.html.twig',
                ['user' => $user]
            );
            $this->mailer->send($email);

            $this->addFlash(
                'success',
                "Un email a été envoyé à l'adresse : " . $user->getEmail()
            );
        } else {
            $this->addFlash(
                'danger',
                'Aucun compte n\'est lié à cet email'
            );

            return $this->redirectToRoute('app_reset_password');
        }

        return $this->render('security/reset_password_request.html.twig');
    }

     /**
     * @Route("/check/{id<\d+>}", name="_check")
     *
     * @return RedirectResponse|Response
     */
    public function check(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // On récupère le token de confirmation
        $token = $request->get('token');
        // Vérification token
        if (empty($token) || $token !== $user->getResetPasswordToken()) {
            $this->addFlash(
                'danger',
                'Votre token n\'est pas valide'
            );
            return $this->redirectToRoute('app_login');
        }
        // Création formulaire
        $form = $this->createForm(ResetPasswordFormType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            // On vide le token
            $user->setResetPasswordToken(null);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Votre mot de passe à bien été réinitialisé'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
