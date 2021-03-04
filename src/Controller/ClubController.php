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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/club")
 */
class ClubController extends AbstractController
{
    private EntityManagerInterface $manager;
    private FileUploader $fileUploader;

    public function __construct(EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $this->entityManager = $manager;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Route("/", name="club_index", methods={"GET"})
     */
    public function index(ClubRepository $clubRepository, LeagueRepository $leagueRepository): Response
    {
        $leagueCurrentUser = $leagueRepository->findOneBy(
            ['responsible' => $this->getUser()]
        );
        // $userWithLeague = $userRepository->findWithLeague($this->getUser()->getId());
        
        return $this->render('club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
            'league' => $leagueCurrentUser
        ]);
    }

    /**
     * @Route("/new", name="club_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER", statusCode=403, message="Accès interdit")
     */
    public function new(Request $request,UserRepository $userRepository, LeagueRepository $leagueRepository): Response
    {
        $users = $userRepository->findWithoutLeague();
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club, [
            'users' => $users
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $leagueCurrentUser = $leagueRepository->findOneBy(
                ['responsible' => $this->getUser()]
            );

            $logo = $request->files->get('club')['logo'];
            //dd($logo);
            //sauvegarde de l'image grâce au service FileUploader
            $fileName = $this->fileUploader->upload($logo, 'logo');
                
            $club->setLeague($leagueCurrentUser);
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
     * @IsGranted("ROLE_USER", statusCode=403, message="Accès interdit")
     */
    public function edit(Request $request, Club $club, UserRepository $userRepository): Response
    {
        $users = $userRepository->findWithoutLeague();
        array_push($users, $club->getSecretary());
        $form = $this->createForm(ClubType::class, $club,[
            'users' => $users
        ]);
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

            $this->addFlash('success', "Le club a bien été mise à jour");

            return $this->redirectToRoute('club_index');
        }

        return $this->render('club/edit.html.twig', [
            'club' => $club,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="club_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER", statusCode=403, message="Accès interdit")
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
