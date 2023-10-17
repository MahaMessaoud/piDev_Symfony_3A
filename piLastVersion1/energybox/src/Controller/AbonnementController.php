<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\User;
use App\Entity\Pack;
use App\Repository\PackRepository;
use App\Form\AbonnementType;
use App\Form\AbonnementExtraType;
use App\Form\AbonnementModifType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\Promotion;
use App\Form\AbonnementTypeNotAdmin;
use App\Form\FormAbnClientType;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;

#[Route('/abonnement')]
class AbonnementController extends AbstractController
{   #[Route('/a', name: 'a')]
    public function getAbs(AbonnementRepository $repo, NormalizerInterface $normalizer){
        $packs=$repo->findAll();
        $packNormalises= $normalizer->normalize($packs,'json',['groups' =>"abonnements"]);
        $json = json_encode($packNormalises);
        return new Response($json);  
    }
    #[Route('/j', name: 'list')]
    public function getAbonnements(AbonnementRepository $repo, NormalizerInterface $normalizer){
        $abonnements=$repo->findAll();
        $abonnementNormalises= $normalizer->normalize($abonnements,'json',['groups' =>"abonnements"]);
        $json = json_encode($abonnementNormalises);
        return new Response($json);
        
    }
    private $entityManager;
   
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
   
    #[Route('/abonnement/{id}', name: 'abonnement')]
    public function AbonnementId(AbonnementRepository $repo, NormalizerInterface $normalizer,$id){
        $abonnements=$repo->find($id);
        $abonnementNormalises= $normalizer->normalize($abonnements,'json',['groups' =>"abonnements"]);
        $json = json_encode($abonnementNormalises);
        return new Response($json);
        
    }
    #[Route('/addAbonnementJson/new', name: 'addAbonnementJson')]
    public function addAbonnementJson(Request $req, NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $abonnement = new Abonnement();
        $abonnement->setDateAchat($req->get('dateAchat'));
        $abonnement->setDateFin($req->get('dateFin'));
        $abonnement->setPack($req->get('pack'));
        $abonnement->setEtatAbonnement($req->get('etatAbonnement'));
        $abonnement->setMontantAbonnement($req->get('montantAbonnement'));
        //user
        $abonnement->setUser($req->get('user'));
        //fin user
        $abonnement->setCodePromo($req->get('codePromo'));
        $em->persist($abonnement);
        $em->flush();
        $abonnementNormalises= $normalizer->normalize($abonnement,'json',['groups' =>"abonnements"]);
        $json = json_encode($abonnementNormalises);
        return new Response($json);
        
    }
    #[Route('/updateAbonnementJson/{id}', name: 'updateAbonnementJson')]
    public function updateAbonnementJson($id,Request $req, NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $abonnement = $em->getRepository(Abonnement::class)->find($id);
        $abonnement->setDateAchat($req->get('dateAchat'));
        $abonnement->setDateFin($req->get('dateFin'));
        $abonnement->setPack($req->get('pack'));
        $abonnement->setEtatAbonnement($req->get('etatAbonnement'));
        $abonnement->setMontantAbonnement($req->get('montantAbonnement'));
        
        $abonnement->setId($abonnement->getId());
        $em->flush();
        $abonnementNormalises= $normalizer->normalize($abonnement,'json',['groups' =>"abonnements"]);
        $json = json_encode($abonnementNormalises);
        return new Response($json);
        
    }
    #[Route('/deleteAbonnementJson/{id}', name: 'deleteAbonnementJson')]
    public function deleteAbonnementJson($id,Request $req, NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $abonnement = $em->getRepository(Abonnement::class)->find($id);
       $em->remove($abonnement);
        $em->flush();
        $abonnementNormalises= $normalizer->normalize($abonnement,'json',['groups' =>"abonnements"]);
        $json = json_encode($abonnementNormalises);
        return new Response("Abonnement supprimé " . $json);
        
    }




    #[Route('/', name: 'app_abonnement_index', methods: ['GET'])]
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
          
        ]);
    }
    #[Route('/c', name: 'app_abonnement_indexClient', methods: ['GET'])]
    public function indexClient(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/indexClient.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
        ]);
    }
    #[Route('/choisirAbonnement', name: 'app_abonnement_choisirAbonnement', methods: ['GET'])]
    public function choisirAbonnement(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/choisirAbonnement.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
        ]);
    }
    public function isValid(string $CodePromotion)
    {
        $promotion = $this->entityManager->getRepository(Promotion::class)->findOneBy(['codePromotion' => $CodePromotion]);
      
        if ($promotion->getCodePromotion() != $CodePromotion) {
            return false;
        }

        return true;
    }
    public function applyDiscount($CodePromotion)
    {
        $promotion = $this->entityManager->getRepository(Promotion::class)->findOneBy(['code' => $CodePromotion]);

        // Appliquez la réduction associée au code promotionnel ici
    }
  

    #[Route('/new', name: 'app_abonnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AbonnementRepository $abonnementRepository,EntityManagerInterface $em): Response

 { 
    // $em = $this->getDoctrine()->getManager();
    //$query = $em->createQuery('SELECT p FROM App\Entity\Promotion p');
    //$promotions = $query->getResult();
    $currentDate = new \DateTime();
    $cr = new \DateTime();
    $formattedDate = $currentDate->format('Y-m-d');
    $abonnement = new Abonnement();
    $dateFin = $abonnement->getDateFin();
    $etatAbonnement = $abonnement->getEtatAbonnement();
    $abonnement->setDateAchat ($currentDate);
    $dateAchat = $abonnement->getDateAchat();
    $form = $this->createForm(AbonnementType::class, $abonnement);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid() ) {
        //user 
        
        $user = $abonnement->getUser();
        $abonnement->setUser($user);
        $abonnementExistant = $this->getDoctrine()->getRepository(Abonnement::class)->findOneBy([
            'user' => $user,
        ]);
        if ($abonnementExistant){
        $etat= $abonnementExistant->getEtatAbonnement();}
        if ($abonnementExistant && $etat =="actif") {
            $response = new Response('<script>alert("l utilisateur a déjà un abonnement en cours. !");
            window.location.href = window.location.href;</script>');
        return $response;
        
        }
        else {// end user tansech }
        $pack = $abonnement->getPack();
        $packduree = $pack->getDureePack();
        $packdisponibilite = $pack->getDisponibilitePack();
        $packplaces = $pack->getPlacesPack();
       if( $packdisponibilite == $packplaces){  
          $response = new Response('<script>alert("Désolé, les places sont épuisées !");
          window.location.href = window.location.href;</script>');
         return $response;}
        if($packdisponibilite > $packplaces){
            $packajout = $packplaces + 1;
            $pack->setPlacesPack($packajout);
            $montantpack = $pack->getMontantPack() ;
            $abonnement->setMontantAbonnement($montantpack);
           $abonnement->setDateFin( $cr->modify(sprintf('+%d month', $packduree )));
    
        $dateFin = $abonnement->getDateFin();
        $formatFin= $dateFin->format('Y-m-d');
        $ff = strtotime($formattedDate);
        $dtt = strtotime($formatFin);
        if ( $ff <= $dtt) { $abonnement->setEtatAbonnement("actif"); }
        if ( $ff > $dtt) { $abonnement->setEtatAbonnement("non actif");}
    
        $codepromo = $abonnement->getCodePromo();
        if ($codepromo != null) {
            $promotion = $this->getDoctrine()->getRepository(Promotion::class)->findOneBy(['codePromotion' => $codepromo]);
            if ($promotion != null && $this->isValid($codepromo)) {
                $promo = $promotion->getReductionPromotion();
                $m = $pack->getMontantPack();
                $promotion1 = $m * $promo;
                $new = $m - $promotion1;
          
            }}
        else{ $new = $pack->getMontantPack() ;
       }
       $abonnement->setMontantAbonnement($new);
        
        $abonnementRepository->save($abonnement, true);
        $em->persist($abonnement);
        $em->flush();
      
        return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }}}
        
  
        return $this->renderForm('abonnement/new.html.twig', [
        'abonnement' => $abonnement,
        'form' => $form,
    ]);
    }
    
   


    #[Route('/newClient', name: 'app_abonnement_newClient', methods: ['GET', 'POST'])]
    public function newClient(Request $request, AbonnementRepository $abonnementRepository,EntityManagerInterface $em): Response
    {  
       // $em = $this->getDoctrine()->getManager();
        //$query = $em->createQuery('SELECT p FROM App\Entity\Promotion p');
        //$promotions = $query->getResult();
        $currentDate = new \DateTime();
        $cr = new \DateTime();
        $formattedDate = $currentDate->format('Y-m-d');
        $abonnement = new Abonnement();
        $dateFin = $abonnement->getDateFin();
        $etatAbonnement = $abonnement->getEtatAbonnement();
        $abonnement->setDateAchat ($currentDate);
        $dateAchat = $abonnement->getDateAchat();
        $form = $this->createForm(FormAbnClientType::class, $abonnement);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid() ) {
            //user 
            //$abonnement->setUser();
            $user = $this->getUser();
            $abonnement->setUser($user);
            $abonnementExistant = $this->getDoctrine()->getRepository(Abonnement::class)->findOneBy([
                'user' => $user,
            ]);
            if ($abonnementExistant){
            $etat= $abonnementExistant->getEtatAbonnement();}
            if ($abonnementExistant && $etat =="actif") {
                return $this->render('abonnement/abonnementExist.html.twig');
            
            }
        
            else {// end user tansech }
            $pack = $abonnement->getPack();
            $packduree = $pack->getDureePack();
            $packdisponibilite = $pack->getDisponibilitePack();
            $packplaces = $pack->getPlacesPack();
            if( $packdisponibilite == $packplaces){   
                return $this->render('abonnement/notavailable.html.twig');}
            elseif ($packdisponibilite > $packplaces){
                $packajout = $packplaces + 1;
                $pack->setPlacesPack($packajout);
                $montantpack = $pack->getMontantPack() ;
                $abonnement->setMontantAbonnement($montantpack);
               $abonnement->setDateFin( $cr->modify(sprintf('+%d month', $packduree )));
        
            $dateFin = $abonnement->getDateFin();
            $formatFin= $dateFin->format('Y-m-d');
            $ff = strtotime($formattedDate);
            $dtt = strtotime($formatFin);
            if ( $ff <= $dtt) { $abonnement->setEtatAbonnement("actif"); }
            if ( $ff > $dtt) { $abonnement->setEtatAbonnement("non actif");}
        
          $codepromo = $abonnement->getCodePromo();
        if ($codepromo != null) {
            $promotion = $this->getDoctrine()->getRepository(Promotion::class)->findOneBy(['codePromotion' => $codepromo]);
            if ($promotion != null && $this->isValid($codepromo)) {
                $promo = $promotion->getReductionPromotion();
                $m = $pack->getMontantPack();
                $promotion1 = $m * $promo;
                $new = $m - $promotion1;
            }}
            else{ $new = $pack->getMontantPack() ;}
                $abonnement->setMontantAbonnement($new); 
            
            $abonnementRepository->save($abonnement, true);
            $em->persist($abonnement);
            $em->flush();
          
            return $this->redirectToRoute('app_abonnement_succ', [], Response::HTTP_SEE_OTHER);
            }}}
        return $this->renderForm('abonnement/newClient.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
        }
        #[Route('/succ', name: 'app_abonnement_succ', methods: ['GET'])]
        public function succ(): Response
        {
            return $this->render('abonnement/abonnementAjoutee.html.twig');
        }
    #[Route('/{id}', name: 'app_abonnement_show', methods: ['GET'])]
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }
    #[Route('/showClient/{id}', name: 'app_abonnement_showClient', methods: ['GET'])]
    public function showClient(Abonnement $abonnement): Response
    {
        

        return $this->render('abonnement/showClient.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }
   
    
    #[Route('/{id}/edit', name: 'app_abonnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonnement $abonnement, AbonnementRepository $abonnementRepository): Response
    {   $currentDate = new \DateTime();
        $formattedDate = $currentDate->format('Y-m-d');
        $form = $this->createForm(AbonnementModifType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateFin = $abonnement->getDateFin();
            $formatFin= $dateFin->format('Y-m-d');
            $ff = strtotime($formattedDate);
            $dtt = strtotime($formatFin);
            if ( $ff <= $dtt) { $abonnement->setEtatAbonnement("actif"); }
            if ( $ff > $dtt) { $abonnement->setEtatAbonnement("non actif");}
          
            $abonnementRepository->save($abonnement, true);
            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_abonnement_delete', methods: ['POST'])]
    public function delete(Request $request, Abonnement $abonnement, AbonnementRepository $abonnementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getId(), $request->request->get('_token'))) {

            $pack = $abonnement->getPack();
            $packplaces = $pack->getPlacesPack();
            $packajout = $packplaces - 1;
            $pack->setPlacesPack($packajout);
            $abonnementRepository->remove($abonnement, true);
        }
        

        return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
    }
    

    #[Route('/ajouter/{id}', name: 'app_abonnement_ajouter')]
    #[ParamConverter('pack', class:'App\Entity\Pack')]
    public function ajouter( Pack $pack,AbonnementRepository $abonnementRepository,EntityManagerInterface $em
    ): Response {
        $currentDate = new \DateTime();
        $cr = new \DateTime();
        $formattedDate = $currentDate->format('Y-m-d');
        $abonnement = new Abonnement();
        $user = $this->getUser();
        $abonnement->setUser($user);
        $abonnementExistant = $this->getDoctrine()->getRepository(Abonnement::class)->findOneBy([
            'user' => $user,
        ]);
        if ($abonnementExistant){
        $etat= $abonnementExistant->getEtatAbonnement();}
        if ($abonnementExistant && $etat =="actif") {
            return $this->render('abonnement/abonnementExist.html.twig');
        
        }
    
        else {
        $abonnement->setPack($pack);
        $etatAbonnement = $abonnement->getEtatAbonnement();
        $abonnement->setDateAchat ($currentDate);
        $abonnement->setUser(
            $this->getUser()
        );
       
        $pack = $abonnement->getPack();
        $packduree = $pack->getDureePack();
        $packdisponibilite = $pack->getDisponibilitePack();
        $packplaces = $pack->getPlacesPack();
       
        if( $packdisponibilite == $packplaces){  
            return $this->render('abonnement/notavailable.html.twig');}
        if ($packdisponibilite > $packplaces){
            $packajout = $packplaces + 1;
            $pack->setPlacesPack($packajout);
            
            $montantpack = $pack->getMontantPack() ;
            $abonnement->setMontantAbonnement($montantpack);
        $abonnement->setDateFin( $cr->modify(sprintf('+%d month', $packduree )));
   
        $dateFin = $abonnement->getDateFin();
        $formatFin= $dateFin->format('Y-m-d');
        $ff = strtotime($formattedDate);
        $dtt = strtotime($formatFin);
        if ( $ff <= $dtt) { $abonnement->setEtatAbonnement("actif"); }
        if ( $ff > $dtt) { $abonnement->setEtatAbonnement("non actif");}

       $new = $pack->getMontantPack() ;
            $abonnement->setMontantAbonnement($new); 
      
        $em->persist($abonnement);
        $em->flush();}
        return $this->redirectToRoute('app_pack_tryindex', [], Response::HTTP_SEE_OTHER);}
        
        return new JsonResponse([
            'success' => true,
            'message' => sprintf('Abonnement for pack "%s" added successfully.', $pack),
            'abonnement' => $abonnement,
        ]);
    }
 

}
