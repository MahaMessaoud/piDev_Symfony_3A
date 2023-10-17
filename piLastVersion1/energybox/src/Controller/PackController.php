<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Entity\Abonnement;
use App\Repository\AbonnementRepository;
use App\Form\PackType;
use App\Form\PackModifType;
use App\Repository\PackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use function random ;
use function floor;
use Winwheel\Winwheel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Knp\Component\Pager\PaginatorInterface;
//require_once './Winwheel.js';

#[Route('/pack')]
class PackController extends AbstractController
{ private $myWheel;
    
    #[Route('/pack/{id}', name: 'pack')]
    public function packId(PackRepository $repo, NormalizerInterface $normalizer,$id){
        $pack=$repo->find($id);
        $packNormalises= $normalizer->normalize($pack,'json',['groups' =>"packs"]);
        $json = json_encode($packNormalises);
        return new Response($json);
    }
    #[Route('/listp', name: 'listp')]
    public function getPacks(PackRepository $repo, NormalizerInterface $normalizer){
        $packs=$repo->findAll();
        $packNormalises= $normalizer->normalize($packs,'json',['groups' =>"packs"]);
        $json = json_encode($packNormalises);
        return new Response($json);  
    }
   
    #[Route('/addPackJson/new', name: 'addPackJson')]
    public function addPackJson(Request $req, NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $pack = new Pack();
        $pack->setTypePack($req->get('typePack'));
        $pack->setMontantPack($req->get('montantPack'));
        $pack->setDureePack((int) $req->get('dureePack'));
        $pack->setDescriptionPack($req->get('descriptionPack'));
        $pack->setPlacesPack((int) $req->get('placesPack'));
        $pack->setDisponibilitePack((int) $req->get('disponibilitePack'));
        var_dump($pack); // ajouté pour déboguer
        $em->persist($pack);
        $em->flush();
        $packNormalises= $normalizer->normalize($pack,'json',['groups' =>"packs"]);
        $json = json_encode($packNormalises);
        return new Response($json);
    }
    
    #[Route('/updatePackJson/{id}', name: 'updatePackJson')]
    public function updatePackJson($id,Request $req, NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $pack = $em->getRepository(Pack::class)->find($id);
        $pack->setTypePack($req->get('typePack'));
        $pack->setMontantPack($req->get('montantPack'));
        $pack->setDureePack((int) $req->get('dureePack'));
        $pack->setDescriptionPack($req->get('descriptionPack'));
        $pack->setPlacesPack((int) $req->get('placesPack'));
        $pack->setDisponibilitePack((int) $req->get('disponibilitePack'));
        $pack->setId($pack->getId());
        $em->flush();
        $packNormalises= $normalizer->normalize($pack,'json',['groups' =>"packs"]);
        $json = json_encode($packNormalises);
        return new Response($json);
        
    }
    #[Route('/deletePackJson/{id}', name: 'deletePackJson')]
    public function deletePackJson($id,Request $req, NormalizerInterface $normalizer){
        $em = $this->getDoctrine()->getManager();
        $pack = $em->getRepository(Pack::class)->find($id);
       $em->remove($pack);
        $em->flush();
        $packNormalises= $normalizer->normalize($pack,'json',['groups' =>"packs"]);
        $json = json_encode($packNormalises);
        return new Response("pack supprimé " . $json);
        
    }



    #[Route('/', name: 'app_pack_index', methods: ['GET'])]
    public function index(PackRepository $packRepository ,AbonnementRepository $abonnementRepository , PaginatorInterface $paginator, Request $request,): Response
    {   $data =$packRepository->findAll() ;
        $packs=$paginator->paginate(
            $data, 
            $request->query->getInt('page',1),
            3
        );
        $packs = $packRepository->findBy([], ['placesPack' => 'DESC']);

        $totalPlaces = 0;
        foreach ($packs as $pack) {
            $totalPlaces += $pack->getPlacesPack();
        }
        $totalPacks = 0;
        foreach ($packs as $pack) {
            $totalPacks += 1;
        }
        $totalDisponibilites = 0;
        foreach ($packs as $pack) {
            $totalDisponibilites += $pack->getDisponibilitePack();
        }
        $abonnements = $abonnementRepository->findAll();
        $totalUtilisateur = 0;
        foreach ($abonnements as $a) {
            $totalUtilisateur += 1;
        }
        $cash = 0;
        foreach ($abonnements as $a) {
            $cash += $a->getMontantAbonnement();
        }

        $packsStats = [];
        $rank = 1;
        foreach ($packs as $pack) {
            if ($totalPlaces > 0){
            $percentage = ($pack->getPlacesPack() / $totalPlaces) * 100;
    
            $packStats = new \stdClass();
            $packStats->pack = $pack;
            $packStats->rank = $rank;
            $packStats->percentage = round($percentage, 2);
            $packsStats[] = $packStats;
    
            $rank++;}
        }
    
        return $this->render('pack/index.html.twig', [
            'packsStats' => $packsStats,
            'packs' => $packRepository->findAll(),
            'total' => $totalPlaces,
            'totald' => $totalDisponibilites,
            'totalpack' => $totalPacks,
            'c'=>$cash,
        ]);
    }
    
    #[Route('/tryindex', name: 'app_pack_tryindex', methods: ['GET'])]
    public function indexClient(
        PackRepository $packRepository , 
        PromotionRepository $promotionRepository,
        Request $request,
        PaginatorInterface $paginator): Response
    { 
    $data =$packRepository->findAll() ;
    $packs=$paginator->paginate(
        $data, 
        $request->query->getInt('page',1),
        3
    );
        return $this->render('pack/tryindex.html.twig', [
            'packs' =>$packs ,
            'promotions' => $promotionRepository->findAll(),
       
        ]);
    }
 
    #[Route('/new', name: 'app_pack_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PackRepository $packRepository): Response
    {
        $pack = new Pack();
        $form = $this->createForm(PackType::class, $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $packRepository->save($pack, true);

            return $this->redirectToRoute('app_pack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pack/new.html.twig', [
            'pack' => $pack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pack_show', methods: ['GET'])]
    public function show(Pack $pack): Response
    {
        return $this->render('pack/show.html.twig', [
            'pack' => $pack,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pack_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pack $pack, PackRepository $packRepository): Response
    {
        $form = $this->createForm(PackModifType::class, $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $packRepository->save($pack, true);

            return $this->redirectToRoute('app_pack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pack/edit.html.twig', [
            'pack' => $pack,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pack_delete', methods: ['POST'])]
    public function delete(Request $request, Pack $pack, PackRepository $packRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pack->getId(), $request->request->get('_token'))) {
            $packRepository->remove($pack, true);
        }

        return $this->redirectToRoute('app_pack_index', [], Response::HTTP_SEE_OTHER);
    }
    
   
    #[Route('/rechercheAjax', name: 'rechercheAjax')]
    public function searchAjax(Request $request, PackRepository $repo)
    {
        // Récupérez le paramètre de recherche depuis la requête
        $query = $request->query->get('q');
    
        // Récupérez le pack correspondant depuis la base de données
        $pack = $repo->findOneBy(['typePack' => $query]);
    
        // Vérifiez si le pack a été trouvé
        if (!$pack) {
            // Si aucun pack correspondant n'a été trouvé, renvoyez une erreur 404
            throw $this->createNotFoundException('Aucun pack correspondant n\'a été trouvé.');
        }
    
        // Générez la réponse au format JSON avec les données du pack
        $response = new JsonResponse([
            'id' => $pack->getId(),
            'typePack' => $pack->getTypePack(),
            'description' => $pack->getDescriptionPack(),
            // Ajoutez ici les autres données que vous souhaitez renvoyer
        ]);
    
        return $response;
    }
    
}
    
    
    
    
    
    

    







