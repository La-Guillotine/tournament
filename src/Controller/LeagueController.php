<?php

namespace App\Controller;

use App\Entity\League;
use App\Form\LeagueType;
use App\Repository\LeagueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }
    
    /**
     * @Route("/", name="league_index", methods={"GET"})
     */
    public function index(Request $request, LeagueRepository $leagueRepository, PaginatorInterface $paginator): Response
    {
        $donnees = $leagueRepository->findAll();
        // Pagination
        $leagues = $paginator->paginate(
            $donnees, // On passe les données, ici les ligues
            $request->query->getInt('page',1), // Numéro de la page en cours, 1 par défaut
            4
        );
        return $this->render('league/index.html.twig', [
            'leagues' => $leagues,
        ]);
    }

    /**
     * @Route("/new", name="league_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Accès interdit")
     */
    public function new(Request $request): Response
    {
        $league = new League();
        $form = $this->createForm(LeagueType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($league);
            $this->entityManager->flush();

            $this->addFlash(
                "success",
                "La Ligue a bien été ajoutée"
            );

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
            $this->entityManager->flush();

            $this->addFlash('success', "La ligue à bien été mise à jour");

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
            $this->entityManager->remove($league);
            $this->entityManager->flush();

            $this->addFlash(
                "success",
                "Suppression réussie"
            );
        }

        return $this->redirectToRoute('league_index');
    }
}
