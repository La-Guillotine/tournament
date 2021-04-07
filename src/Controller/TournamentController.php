<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Form\TournamentType;
use App\Repository\InscriptionRepository;
use App\Repository\UserRepository;
use App\Repository\LeagueRepository;
use App\Repository\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tournament")
 */
class TournamentController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    /**
     * @Route("/", name="tournament_index", methods={"GET"})
     */
    public function index(Request $request, TournamentRepository $tournamentRepository, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        // Tournois sortis par date de début de la plus proche à la plus lointaine
        $donnees = $tournamentRepository->findBy(
            [],
            ['start_date' => 'ASC']
        );

        // Pagination
        $tournaments = $paginator->paginate(
            $donnees, // on passe les données, ici les tournois
            $request->query->getInt('page', 1), // Numéro de la page en cours, 1 par défaut
            8
        );

        return $this->render('tournament/index.html.twig', [
            'tournaments' => $tournaments,
            'users' => $userRepository->getUsersWithLeague()
        ]);
    } 

    /**
     * @Route("/new", name="tournament_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER') and user in userRepository.getUsersWithLeague()", statusCode=403, message="Accès interdit")
     */
    public function new(Request $request, UserRepository $userRepository, LeagueRepository $leagueRepository): Response
    {
        $tournament = new Tournament();
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournament->setLeague($leagueRepository->findOneBy(['responsible' => $this->getUser()]));
            $this->entityManager->persist($tournament);
            $this->entityManager->flush();

            $this->addFlash(
                "success",
                "Le Tournoi a bien été ajouté"
            );

            return $this->redirectToRoute('tournament_index');
        }

        return $this->render('tournament/new.html.twig', [
            'tournament' => $tournament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournament_show", methods={"GET"})
     */
    public function show(Tournament $tournament, InscriptionRepository $inscriptionRepository): Response
    {
        $clubsInscrits = [];
        $inscriptions = $inscriptionRepository->findBy(['tournament' => $tournament]);
        foreach($inscriptions as $inscription){
            array_push($clubsInscrits, $inscription->getClub());
        }

        return $this->render('tournament/show.html.twig', [
            'tournament' => $tournament,
            'clubsInscrits' => $clubsInscrits
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tournament_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or tournament.getLeague().getResponsible() == user")
     */
    public function edit(Request $request, Tournament $tournament): Response
    {
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash(
                "success",
                "Mise à jour effectuée avec succès"
            );

            return $this->redirectToRoute('tournament_index');
        }

        return $this->render('tournament/edit.html.twig', [
            'tournament' => $tournament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournament_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tournament $tournament): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournament->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($tournament);
            $this->entityManager->flush();

            $this->addFlash(
                "success",
                "Suppression réussie"
            );
        }

        return $this->redirectToRoute('tournament_index');
    }
}
