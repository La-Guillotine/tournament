<?php

namespace App\Controller;

use App\Entity\League;
use App\Form\LeagueType;
use App\Repository\LeagueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/league")
 */
class LeagueController extends AbstractController
{
    /**
     * @Route("/", name="league_index", methods={"GET"})
     */
    public function index(LeagueRepository $leagueRepository): Response
    {
        return $this->render('league/index.html.twig', [
            'leagues' => $leagueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="league_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Accès interdit")
     */
    public function new(Request $request): Response
    {
        $league = new League();
        $form = $this->createForm(LeagueType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($league);
            $entityManager->flush();

            return $this->redirectToRoute('league_index');
        }

        return $this->render('league/new.html.twig', [
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="league_show", methods={"GET"})
     */
    public function show(League $league): Response
    {
        return $this->render('league/show.html.twig', [
            'league' => $league,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="league_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or league.getResponsible() == user")
     */
    public function edit(Request $request, League $league): Response
    {
        $form = $this->createForm(LeagueType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('league_index');
        }

        return $this->render('league/edit.html.twig', [
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="league_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Accès interdit")
     */
    public function delete(Request $request, League $league): Response
    {
        if ($this->isCsrfTokenValid('delete'.$league->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($league);
            $entityManager->flush();
        }

        return $this->redirectToRoute('league_index');
    }
}
