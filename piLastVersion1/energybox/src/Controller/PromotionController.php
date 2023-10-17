<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Route('/promotion')]
class PromotionController extends AbstractController
{
    #[Route('/j', name: 'list')]
    public function getPromotions(PromotionRepository $repo, NormalizerInterface $normalizer){
        $promotions=$repo->findAll();
        $promotionNormalises= $normalizer->normalize($promotions,'json',['groups' =>"promotions"]);
        $json = json_encode($promotionNormalises);
        return new Response($json);
        
    }
    #[Route('/promotion/{id}', name: 'promotion')]
    public function promotionId(PromotionRepository $repo, NormalizerInterface $normalizer,$id){
        $promotion=$repo->find($id);
        $promotionNormalises= $normalizer->normalize($promotion,'json',['groups' =>"promotions"]);
        $json = json_encode($promotionNormalises);
        return new Response($json);
        
    }
    #[Route('/addPromotionJson/new', name: 'addPromotionJson')]
    public function addPromotionJson(Request $req, NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $promotion = new Promotion();
        $promotion->setCodePromotion($req->get('codePromotion'));
        $promotion->setReductionPromotion($req->get('reductionPromotion'));
        $promotion->setDateExpiration($req->get('dateExpiration'));
    
        $em->persist($promotion);
        $em->flush();
        $promotionNormalises= $normalizer->normalize($promotion,'json',['groups' =>"promotions"]);
        $json = json_encode($promotionNormalises);
        return new Response($json);
        
    }
    #[Route('/updatePromotionJson/{id}', name: 'updatePromotionJson')]
    public function updatePromotionJson($id,Request $req, NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $promotion = $em->getRepository(Promotion::class)->find($id);
        $promotion->setCodePromotion($req->get('codePromotion'));
        $promotion->setReductionPromotion($req->get('reductionPromotion'));
        $promotion->setDateExpiration($req->get('dateExpiration'));
        $promotion->setId($pack->getId());
        $em->flush();
        $promotionNormalises= $normalizer->normalize($promotion,'json',['groups' =>"promotions"]);
        $json = json_encode($promotionNormalises);
        return new Response($json);
    }

    #[Route('/deletePromotionJson/{id}', name: 'deletePromotionJson')]
    public function deletePromotionJson($id,Request $req, NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $promotion = $em->getRepository(Promotion::class)->find($id);
        $em->remove($promotion);
        $em->flush();
        $promotionNormalises= $normalizer->normalize($promotion,'json',['groups' =>"promotions"]);
        $json = json_encode($promotionNormalises);
        return new Response("promotion supprimé " . $json);
        
    }


    #[Route('/', name: 'app_promotion_index', methods: ['GET'])]
    public function index(PromotionRepository $promotionRepository): Response
    {
        return $this->render('promotion/index.html.twig', [
            'promotions' => $promotionRepository->findAll(),
        ]);
    }
   
    public function isValid(string $CodePromotion)
    {
        $promotion = $this->entityManager->getRepository(Promotion::class)->findOneBy(['codePromotion' => $CodePromotion]);

        if (!$promotion || $promotion->isExpired()) {
            return false;
        }

        return true;
    }


    #[Route('/new', name: 'app_promotion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PromotionRepository $promotionRepository): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotionRepository->save($promotion, true);

            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion/new.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_promotion_show', methods: ['GET'])]
    public function show(Promotion $promotion): Response
    {
        return $this->render('promotion/show.html.twig', [
            'promotion' => $promotion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_promotion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Promotion $promotion, PromotionRepository $promotionRepository): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promotionRepository->save($promotion, true);

            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_promotion_delete', methods: ['POST'])]
    public function delete(Request $request, Promotion $promotion, PromotionRepository $promotionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotion->getId(), $request->request->get('_token'))) {
            $promotionRepository->remove($promotion, true);
        }

        return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
    }
    
  
    public function applyDiscount($CodePromotion)
    {
        $promotion = $this->entityManager->getRepository(Promotion::class)->findOneBy(['code' => $CodePromotion]);

        // Appliquez la réduction associée au code promotionnel ici
    }
}
