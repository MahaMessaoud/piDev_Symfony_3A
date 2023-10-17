<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Menu;
use App\Repository\MenuRepository;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;

class PlatController extends AbstractController
{
    #[Route('/plat', name: 'app_plat')]
    public function index(): Response
    {
        return $this->render('plat/index.html.twig', [
            'controller_name' => 'PlatController',
        ]);
    }
//AffichageBack
#[Route('/ListPlat', name: 'app_ListPlat')]
public function ListPlat(PlatRepository $repository, Request $request, PaginatorInterface $paginator)
{
    $plat = $paginator->paginate(
        $plat = $repository->findAll(),
        $page = $request->query->getInt('page', 1),
        2
    );

    if ($request->isXmlHttpRequest()) {
        $html = $this->renderView("plat/listPlat.html.twig", [
            "tabplat" => $plat,
        ]);

        return new JsonResponse($html);
    } else {
        return $this->render("plat/listPlat.html.twig", [
            "tabplat" => $plat,
        ]);
    }
}
#[Route('/RechercheMobile/{prix}', name: 'app_RechercheMobile')]
    public function RechercheMobile($prix,PlatRepository $repo,NormalizerInterface $normalizer)
    {
        $plat=$repo->RechercheMobile($prix);
        $platNormalises=$normalizer->normalize($plat,'json',['groups' => "plat"]);
        $json=json_encode($platNormalises);
        return new Response($json);
    }

//AFFICHAGE_Front
    #[Route('/ListPlatFront/{id}', name: 'app_ListPlatFront')]
    public function ListPlatFront(PlatRepository $repository, Request $request, PaginatorInterface $paginator,Menu $categories,$id)
    {
        $plat = $paginator->paginate(
            $plat = $repository->filtrage($categories->getId()),
            $page = $request->query->getInt('page', 1),
            2
        );

        if ($request->isXmlHttpRequest()) {
            $html = $this->renderView("plat/filtrage.html.twig", [
                "tabplat" => $plat,
            ]);

            return new JsonResponse($html);
        } else {
            return $this->render("plat/filtrage.html.twig", [
                "tabplat" => $plat,
            ]);
        }
    }

/*
    #[Route('/ListPlatFront/{id}', name: 'app_ListPlatFront')]
    public function ListPlatF(PlatRepository $repository, Menu $categories, $id,Request $request,PaginatorInterface $paginator)
    {
        $plat = $repository->filtrage($categories->getId());
        $plat = $paginator->paginate(
            $plat,
            $request->query->getInt('page', 1),
            3);
        return $this->render("plat/filtrage.html.twig", array("tabplat" => $plat));
    }
*/
//AJOUT
    #[Route('/addplat', name: 'app_addplat')]
    public function addplat(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);
        $filesystem = new Filesystem();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($plat);
            $em->flush();
            $uploadedFile = $form->get('image')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'plat_images/photo' . strval($plat->getId()) . '.png';
            $plat->setImage($destinationPath);
            $filesystem->copy($sourcePath, $destinationPath);
            $em->persist($plat);
            $em->flush();
            return $this->redirectToRoute('app_ListPlat');
            $this->addFlash('success', 'Le plat a été ajouté avec succès.');
        }
        return $this->renderForm("plat/addplat.html.twig",
            array("formPlat" => $form));
    }

//MODIFICATION
    #[Route('/updateplat/{id}', name: 'app_updateplat')]
    public function updateplat(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request, PlatRepository $repository, $id)
    {

        $plat = $repository->find($id);
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);
        $filesystem = new Filesystem();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            /* img*/
            $uploadedFile = $form->get('image')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'plat_images/photo' . strval($plat->getId()) . '.png';
            $plat->setImage($destinationPath);
            $filesystem->copy($sourcePath, $destinationPath);
            /* */
            $em->flush();
            return $this->redirectToRoute('app_ListPlat');
        }
        return $this->renderForm("plat/updateplat.html.twig", array("formPlat" => $form));
    }

//Suppression
    #[Route('/removePlat/{id}', name: 'app_removePlat')]
    public function removeplat(ManagerRegistry $mg, PlatRepository $repository, $id)
    {
        $plat = $repository->find($id);
        $em = $mg->getManager();
        $em->remove($plat);
        $em->flush();
        return $this->redirectToRoute('app_ListPlat');
    }

//Tri_Back
    #[Route('/TriPAB', name: 'app_tri_prixA')]
    public function TriPBackA(PlatRepository $repository,PaginatorInterface $paginator,Request $request)
    {
        $plat = $paginator->paginate(
        $plat = $repository->orderByPrixASC(),
        $request->query->getInt('page', 1),
            3);
        return $this->render("plat/listPlat.html.twig", array("tabplat" => $plat));
    }

    #[Route('/TriPDB', name: 'app_tri_prixD')]
    public function TriPBackD(PlatRepository $repository,PaginatorInterface $paginator,Request $request)
    {
            $plat = $paginator->paginate(
            $plat = $repository->orderByPrixDESC(),
            $request->query->getInt('page', 1),
            3);
        return $this->render("plat/listPlat.html.twig", array("tabplat" => $plat));
    }

    #[Route('/TriNAB', name: 'app_tri_nomA')]
    public function TriNBackA(PlatRepository $repository,PaginatorInterface $paginator,Request $request)
    {
            $plat = $paginator->paginate(
            $plat = $repository->orderByNomASC(),
            $request->query->getInt('page', 1),
            3);
        return $this->render("plat/listPlat.html.twig", array("tabplat" => $plat));
    }

    #[Route('/TriNDB', name: 'app_tri_nomD')]
    public function TriNBackD(PlatRepository $repository,PaginatorInterface $paginator , Request $request)
    {
            $plat = $paginator->paginate(
            $plat = $repository->orderByNomDESC(),
            $request->query->getInt('page', 1),
            3);
        return $this->render("plat/listPlat.html.twig", array("tabplat" => $plat));
    }

    #[Route('/TriNbBA', name: 'app_tri_nbpA')]
    public function TriCBackA(PlatRepository $repository,PaginatorInterface $paginator,Request $request)
    {
            $plat = $paginator->paginate(
            $plat = $repository->orderByNombreASC(),
            $request->query->getInt('page', 1),
            3);
        return $this->render("plat/listPlat.html.twig", array("tabplat" => $plat));
    }

    #[Route('/TriNbBD', name: 'app_tri_nbpD')]
    public function TriCBackD(PlatRepository $repository,PaginatorInterface $paginator,Request $request)
    {
            $plat = $paginator->paginate(
                $plat = $repository->orderByNombreDESC(),
            $request->query->getInt('page', 1),
            3);
        return $this->render("plat/listPlat.html.twig", array("tabplat" => $plat));
    }

//imprimerMenu
    #[Route('/pdf/{id}', name: 'PDF_Plat')]
    public function pdf(PlatRepository $Repository,$id)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Open Sans');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $plat = $Repository->find($id);
        $html = $this->renderView('plat/pdf.html.twig', [
            'plat' => [$plat],
        ]);

        // Add header HTML to $html variable
        $headerHtml = '<h1 style="text-align: center; color: #b00707;">Bienvenue chez EnergyBox Restaurant</h1>';
        $html = $headerHtml . $html;

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        // Send the PDF to the browser
        $response = new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="MonPlat.pdf"',
        ]);

        return $response;
    }

//QR_Code
    #[Route('/QrCode/{id}', name: 'app_QrCode')]
    public function qrCode(ManagerRegistry $doctrine, $id, PlatRepository $repo)
    {
        return $this->render("plat/QrCode.html.twig", ['id' => $id]);
    }

    #[Route('/QrCode/generate/{id}', name: 'app_qr_codes')]
    public function qrGenerator(ManagerRegistry $doctrine, $id, PlatRepository $repo)
    {
        $em = $doctrine->getManager();
        $res = $repo->find($id);
      //  $qrcode = QrCode::create($res->getNom() .  " Et le prix est: " . $res->getPrix())
        $qrcode = QrCode::create( " - Nom du plat:". $res->getNom() . " , Le prix est: " . $res->getPrix() . " DT")

            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $writer = new PngWriter();
        return new Response($writer->write($qrcode)->getString(),
            Response::HTTP_OK,
            ['content-type' => 'image/png']
        );

    }
//Rercherche back
    #[Route('/rechercheAjax', name: 'rechercheAjax')]
    public function searchAjax(Request $request,PlatRepository $repo,PaginatorInterface $paginator)
    {
        // Récupérez le paramètre de recherche depuis la requête
        $query = $request->query->get('q');
        // Récupérez les plats correspondants depuis la base de données
        $plats = $paginator->paginate(
            $repo->findPlatByName($query), /* query NOT result */
            $request->query->getInt('page', 1),
            3);
        $html =  $this->renderView("plat/listPlat.html.twig",[
            "tabplat" => $plats,
        ]);

         return new Response($html);
    }
//Rercherche front
    #[Route('/rechercheAjaxFront', name: 'rechercheAjaxFront')]
    public function searchAjaxFront(Request $request, PlatRepository $repo, PaginatorInterface $paginator)
    {
        // Récupérez le paramètre de recherche depuis la requête
        $query = $request->query->get('q');
        $categories = $request->query->get('categories');

        // Récupérez les plats correspondants depuis la base de données
        $plats = $paginator->paginate(
            $repo->findPlatByNameFront($query, $categories), /* query NOT result */
            $request->query->getInt('page', 1),
            3);

        $html = $this->renderView("plat/filtrage.html.twig", [
            "tabplat" => $plats,
        ]);

        return new Response($html);
    }

 /*   public function searchAjaxFront(Request $request,PlatRepository $repo,PaginatorInterface $paginator)
    {
        // Récupérez le paramètre de recherche depuis la requête
        $query = $request->query->get('q');
        // Récupérez les plats correspondants depuis la base de données
        $plats = $paginator->paginate(
            $repo->findPlatByNameFront($query),
            $request->query->getInt('page', 1),
            3);
        $html =  $this->renderView("plat/filtrage.html.twig",[
            "tabplat" => $plats,
        ]);

        return new Response($html);
    }
*/
//WorkShopJson
    #[Route('/AllPlatsjson', name: 'app_AllPlatjson')]
    public function AllplatsJson(PlatRepository $repo,NormalizerInterface $normalizer)
    {
        $plat=$repo->findAll();
        $platNormalises=$normalizer->normalize($plat,'json',['groups' => "plat"]);
        $json=json_encode($platNormalises);
        return new Response($json);
    }
    #[Route('/Platsjson/{id}', name: 'app_Platjson')]
    public function PlatsidJson(PlatRepository $repo,SerializerInterface $normalizer,$id)
    {
        $plat=$repo->find($id);
        $platNormalises=$normalizer->serialize($plat,'json',['groups' => "plat"]);
        $json=json_encode($platNormalises);
        return new Response($json);
    }
    #[Route('/AddPlatsjson/new', name: 'app_AddPlatjson')]
    public function AddPlatsJson(SerializerInterface $normalizer,Request $request,ManagerRegistry $mg,MenuRepository $menu)
    {
          $em = $mg->getManager();
     //   $em=$mg=$this->getManger();
        $plat=new Plat();
        $plat->setNom($request->get('nom'));
        $plat->setprix($request->get('prix'));
        $plat->setDescription($request->get('desc'));
        $plat->setCalories($request->get('calories'));
        $plat->setEtat($request->get('etat'));
        $plat->setNbp($request->get('nbp'));
        $plat->setImage($request->get('image'));
        $plat->setCategories($menu->find(2));
       // $plat->setUserId($request->get('user'));
        $em->persist($plat);
        $em->flush();

        $jsonContent=$normalizer->serialize($plat,'json',['groups' => "plat"]);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/UpatePlatsjson', name: 'app_UpdatePlatjson')]
    public function UpdatePlatsJson(NormalizerInterface $normalizer,Request $request,ManagerRegistry $mg)
    {
        $em = $mg->getManager();
        $plat=$em->getRepository(Plat::class)->find($request->get('id'));

        $plat->setNom($request->get('nom'));
        $plat->setprix($request->get('prix'));
       $plat->setDescription($request->get('desc'));
        $plat->setCalories($request->get('calories'));
        $plat->setEtat($request->get('etat'));
        $plat->setNbp($request->get('nbp'));
        $plat->setImage($request->get('image'));
     //   $plat->setUserId($request->get('user'));
        $em->flush();

        $jsonContent=$normalizer->normalize($plat,'json',['groups' => "plat"]);
        return new Response("Plat mise à jour avec succés" . json_encode($jsonContent));
    }

    #[Route('/DeletePlatsjson', name: 'app_DeletePlatjson')]
    public function DeletePlatsJson(NormalizerInterface $normalizer,Request $request,ManagerRegistry $mg)
    {
        $em = $mg->getManager();
        $plat=$em->getRepository(Plat::class)->find($request->get('id'));
        $em->remove($plat);
        $em->flush();
        $jsonContent=$normalizer->normalize($plat,'json',['groups' => "plat"]);
        return new Response("Plat supprimé avec succés" . json_encode($jsonContent));
    }
    #[Route('/AllPlatsjsonTri', name: 'app_AllPlatjsonTri')]
    public function AllplatsJsonTri(PlatRepository $repo,NormalizerInterface $normalizer)
    {
        $plat=$repo->orderByPrixASC();
        $platNormalises=$normalizer->normalize($plat,'json',['groups' => "plat"]);
        $json=json_encode($platNormalises);
        return new Response($json);
    }

}