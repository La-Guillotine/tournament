<?php

namespace App\Controller;

use Exception;
use App\Entity\Club;
use App\Entity\Tournament;
use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\ClubRepository;
use App\Repository\LeagueRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InscriptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route("/inscription")
 */
class InscriptionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    /**
     * @Route("/new/{tournamentId}/{clubId}", name="inscription_new", methods={"POST"})
     * @Entity("tournament", expr="repository.find(tournamentId)")
     * @Entity("club", expr="repository.find(clubId)")
     */
    public function new(Request $request, Tournament $tournament, Club $club): RedirectResponse
    {
        
            $inscription = new Inscription();
            $inscription->setClub($club);
            $inscription->setTournament($tournament);
            $inscription->setStatus('done');
            
            $this->entityManager->persist($inscription);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                "Votre club ({$club->getName()}) a bien été ajouté au tournoi : {$tournament->getTitle()}."
            );

            return $this->redirectToRoute('tournament_show', [
                'id' => $tournament->getId()
            ]);
           
        // }catch(Exception $e){
        //     $this->addFlash(
        //         'error',
        //         $e
        //     );

        //     return $this->redirectToRoute('tournament_show', [
        //         'id' => $tournament->getId()
        //     ]);
        // }      
        
    }
}
