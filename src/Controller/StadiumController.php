<?php

namespace App\Controller;

use Exception;
use App\Entity\Stadium;
use App\Form\StadiumType;
use App\Service\FileUploader;
use App\Repository\StadiumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/stadium")
 */
class StadiumController extends AbstractController
{
    private EntityManagerInterface $manager;
    private FileUploader $fileUploader;

    public function __construct(EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $this->entityManager = $manager;
        $this->fileUploader = $fileUploader;
    }
    /**
     * @Route("/", name="stadium_index", methods={"GET"})
     */
    public function index(Request $request, StadiumRepository $stadiumRepository, PaginatorInterface $paginator): Response
    {
        $donnees = $stadiumRepository->findAll();

        //Pagination
        $stadiums = $paginator->paginate(
            $donnees, // On passe les données, ici les stades
            $request->query->getInt('page', 1), // Numéro de la page en cours, 1 par défaut
            6
        );
        return $this->render('stadium/index.html.twig', [
            'stadiums' => $stadiums,
        ]);
    }

    /**
     * @Route("/new", name="stadium_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Accès interdit")
     */
    public function new(Request $request): Response
    {
        $stadium = new Stadium();
        $form = $this->createForm(StadiumType::class, $stadium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            try{
                $picture = $request->files->get('stadium')['picture'];
                //dd($picture);
                //sauvegarde de l'image grâce au service FileUploader
                $fileName = $this->fileUploader->upload($picture, 'stadium');
                    
                $stadium->setPicture($fileName);

                $this->entityManager->persist($stadium);
                $this->entityManager->flush();

                $this->addFlash(
                    "success",
                    "Le Stade a bien été ajouté"
                );

            
            }catch(Exception $e){
                $this->addFlash(
                    "danger",
                    $e
                );
            }
            return $this->redirectToRoute('stadium_index');
        }

        return $this->render('stadium/new.html.twig', [
            'stadium' => $stadium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stadium_show", methods={"GET"})
     */
    public function show(Stadium $stadium): Response
    {
        return $this->render('stadium/show.html.twig', [
            'stadium' => $stadium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stadium_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Accès interdit")
     */
    public function edit(Request $request, Stadium $stadium): Response
    {
        $form = $this->createForm(StadiumType::class, $stadium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $request->files->get('stadium')['picture'];
            if($picture !== null){
                $this->fileUploader->removeUpload($stadium->getPicture(), 'stadium');
                //sauvegarde de l'image grâce au service FileUploader
                $fileName = $this->fileUploader->upload($picture, 'stadium');
                    
                $stadium->setPicture($fileName);
            }
           
            $this->entityManager->flush();

            $this->addFlash(
                "success",
                "Mise à jour du stade effectuée avec succès"
            );

            return $this->redirectToRoute('stadium_index');
        }

        return $this->render('stadium/edit.html.twig', [
            'stadium' => $stadium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stadium_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Accès interdit")
     */
    public function delete(Request $request, Stadium $stadium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stadium->getId(), $request->request->get('_token'))) {
            $this->fileUploader->removeUpload($stadium->getPicture(), 'stadium');
            $this->entityManager->remove($stadium);
            $this->entityManager->flush();

            $this->addFlash(
                "success",
                "Suppression réussie"
            );
        }

        return $this->redirectToRoute('stadium_index');
    }
}
