<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/account", name="account_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Accès interdit")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or userShow == user")
     */
    public function show(User $userShow): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $userShow,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or userEdit == user")
     */
    public function edit(Request $request, User $userEdit): Response
    {
        $form = $this->createForm(UserType::class, $userEdit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Vos informations ont bien été mise à jour");

            return $this->render('user/show.html.twig', [
                'user' => $userEdit
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $userEdit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN') or userDelete == user")
     */
    public function delete(Request $request, User $userDelete): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userDelete->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userDelete);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur à bien été supprimer");
        }

        return $this->render('user/show.html.twig', [
            'user' => $userDelete
        ]);
    }
}
