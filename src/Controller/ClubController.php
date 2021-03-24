<?php

namespace App\Controller;

use Exception;
use App\Entity\Club;
use App\Form\ClubType;
use App\Service\FileUploader;
use App\Repository\ClubRepository;
use App\Repository\UserRepository;
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
 * @Route("/club")
 */
class ClubController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private FileUploader $fileUploader;

    public function __construct(EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $this->entityManager = $manager;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Route("/", name="club_index", methods={"GET"})
     */
    public function index(Request $request, ClubRepository $clubRepository, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $usersWithoutClubsAndLeague = $userRepository->findWithoutLeague();

        $donnees = $clubRepository->findAll();
        $clubs = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1), // Numéro de la page en cours, 1 par défaut
            8
        );
        
        return $this->render('club/index.html.twig', [
            'clubs' => $clubs,
            'users' => $usersWithoutClubsAndLeague
        ]);
    }

    /**
     * @Route("/new", name="club_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER') and (user in userRepository.findWithoutLeague())", statusCode=403, message="Accès interdit")
     */
    public function new(Request $request,UserRepository $userRepository, LeagueRepository $leagueRepository): Response
    {
        // $users = $userRepository->findWithoutLeague();
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // $leagueCurrentUser = $leagueRepository->findOneBy(
            //     ['responsible' => $this->getUser()]
            // );

            $logo = $request->files->get('club')['logo'];
            //dd($logo);
            //sauvegarde de l'image grâce au service FileUploader
            $fileName = $this->fileUploader->upload($logo, 'logo');
                
            $club->setSecretary($this->getUser());
            $club->setLogo($fileName);

            $this->entityManager->persist($club);
            $this->entityManager->flush();

            $this->addFlash(
                "success",
                "Le Club a bien été ajouté"
            );

            return $this->redirectToRoute('club_index');
        }

        return $this->render('club/new.html.twig', [
            'club' => $club,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="club_show", methods={"GET"})
     */
    public function show(Club $club): Response
    {
        return $this->render('club/show.html.twig', [
            'club' => $club,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="club_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or club.getSecretary() == user", statusCode=403, message="Accès interdit")
     */
    public function edit(Request $request, Club $club): Response
    {
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $request->files->get('club')['logo'];
            if($picture !== null){
                $this->fileUploader->removeUpload($club->getLogo(), 'logo');
                //sauvegarde de l'image grâce au service FileUploader
                $fileName = $this->fileUploader->upload($picture, 'logo');
                    
                $club->setLogo($fileName);
            }

            $this->entityManager->flush();

            $this->addFlash('success', "Mise à jour du club effectuée avec succès");

            return $this->redirectToRoute('club_index');
        }

        return $this->render('club/edit.html.twig', [
            'club' => $club,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="club_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN') or club.getSecretary() == user", statusCode=403, message="Accès interdit")
     */
    public function delete(Request $request, Club $club): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($club);
            $this->entityManager->flush();

            $this->addFlash('success', "Le club a bien été supprimé");
        }

        return $this->redirectToRoute('club_index');
    }
}
