<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/', name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    /* JSON Add */
    #[Route('/newJSON', name: 'addJSONReponse')]
    public function newJSON(Request $request, NormalizerInterface $Normalizer, EntityManagerInterface $em)
    {
        $em =$this->getDoctrine()->getManager();
        $reponse = new Reponse();
        $dateString=$request->get('dateReponse');
        $reponseDate=new DateTime($dateString);
        $reponse->setObjetReponse($request->get('objetReponse'));
        $reponse->setDateReponse($reponseDate);
        $reponse->setPieceJointe($request->get('pieceJointe'));
        $reponse->setContenuReponse($request->get('contenuReponse'));

        $em->persist($reponse);
        $em->flush();

        $jsonContent = $Normalizer->normalize($reponse,'json',['groups'=>'reponse']);

        return new Response("Reponse ajouté avec succés".json_encode($jsonContent));
    }

    /* JSON View */
    #[Route('/viewJSON', name: 'viewJSONReponse')]
    public function viewJSON(NormalizerInterface $Normalizer, ReponseRepository $repo)
    {
        $reponse = $repo->findAll();

        $reponseNormalises = $Normalizer->normalize($reponse,'json',['groups'=>'reponse']);

        $json = json_encode($reponseNormalises);
        
        return new Response($json);
        
    }

    #[Route('/new/{id}', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReponseRepository $reponseRepository,Reclamation $reclamation): Response
    {
        $reponse = new Reponse();
        $filesystem = new Filesystem();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('pieceJointe')->getData();
            $formData =  $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/file'.$reponse->getObjetReponse().strval($reponse->getId()).'.txt';
            $reponse->setPieceJointe($destinationPath);
            $filesystem->copy($sourcePath, $destinationPath);

            $reponse->setDateReponse(new DateTime());
            $reponse->setReclamation($reclamation);
            $reponseRepository->save($reponse, true);

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        $filesystem = new Filesystem();
        
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('pieceJointe')->getData();
            $formData =  $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/file'.$reponse->getObjetReponse().strval($reponse->getId()).'.txt';
            $reponse->setPieceJointe($destinationPath);
            $filesystem->copy($sourcePath, $destinationPath);


            $reponseRepository->save($reponse, true);
            

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $reponseRepository->remove($reponse, true);
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
}
