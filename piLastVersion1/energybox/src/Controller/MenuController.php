<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Plat;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(): Response
    {
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }

 //AFFICHAGE
    #[Route('/ListMenu', name: 'app_ListMenu')]
    public function ListMenu(MenuRepository $repository)
    {
        $menu = $repository->findAll();
        return $this->render("menu/listMenu.html.twig", array("tabmenu" => $menu));
    }
//AJOUT
    #[Route('/addmenu', name: 'app_addmenu')]
    public function addmenu(\Doctrine\Persistence\ManagerRegistry $doctrine, Request $request)
    {

        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $em = $doctrine->getManager();
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute('app_ListMenu');
        }
        return $this->renderForm("menu/addmenu.html.twig",
            array("formmenu" => $form));
    }

//Suppression
    #[Route('/removemenu/{id}', name: 'app_removemenu')]
    public function removeMenu(ManagerRegistry $mg,MenuRepository $repository,$id)
    {
        $menu=$repository->find($id);
        $em = $mg->getManager();
        $em->remove($menu);
        $em->flush();
        return $this->redirectToRoute('app_ListMenu');
    }

//MODIFICATION
    #[Route('/updatemenu/{id}', name: 'app_updatemenu')]
    public function updateplat(\Doctrine\Persistence\ManagerRegistry $doctrine, Request  $request, MenuRepository $repository, $id)
    {

        $menu = $repository->find($id);
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_ListMenu');
        }
        return $this->renderForm("menu/updatemenu.html.twig",array("formmenu"=>$form));
    }
//Tri_Back
    #[Route('/TriNMAB', name: 'app_tri_nomMA')]
    public function TriNMBackA(MenuRepository $repository){
        $plat=$repository->orderBynomA();
        return $this->render("menu/listMenu.html.twig", array("tabmenu" => $plat));
    }
    #[Route('/TriNMDB', name: 'app_tri_nomMD')]
    public function TriNMBackD(MenuRepository $repository){
        $plat=$repository->orderBynomD();
        return $this->render("menu/listMenu.html.twig", array("tabmenu" => $plat));
    }
    /*
//WorkShopJson
    #[Route('/AllMenusjson', name: 'app_AllMenujson')]
    public function AllMenusJson(MenuRepository $repo,NormalizerInterface $normalizer)
    {
        $menu=$repo->findAll();
        $menuNormalises=$normalizer->normalize($menu,'json',['groups' => "menu"]);
        $json=json_encode($menuNormalises);
        return new Response($json);
    }
    #[Route('/Menusjson/{id}', name: 'app_Menujson')]
    public function MenusidJson(MenuRepository $repo,NormalizerInterface $normalizer,$id)
    {
        $menu=$repo->find($id);
        $menuNormalises=$normalizer->normalize($menu,'json',['groups' => "menu"]);
        $json=json_encode($menuNormalises);
        return new Response($json);
    }
    #[Route('/AddMenujson/new', name: 'app_AddMenujson')]
    public function AddMenuJson(NormalizerInterface $normalizer,Request $request,ManagerRegistry $mg)
    {
        $em = $mg->getManager();
        $menu=new Menu();
        $menu->setCategories($request->get('nom'));
        $menu->setDescriptionmenu($request->get('desc'));
        $menu->setUserId($request->get('user'));
        $em->persist($menu);
        $em->flush();

        $jsonContent=$normalizer->normalize($menu,'json',['groups' => "menu"]);
        return new Response("Menu ajouté avec succés" .json_encode($jsonContent));
    }

    #[Route('/UpateMenujson/{id}', name: 'app_UpdateMenujson')]
    public function UpdateMenuJson(NormalizerInterface $normalizer,Request $request,ManagerRegistry $mg,$id)
    {
        $em = $mg->getManager();
        $menu=$em->getRepository(Menu::class)->find($id);

        $menu->setCategories($request->get('nom'));
        $menu->setDescriptionmenu($request->get('desc'));
        $menu->setUserId($request->get('user'));

        $em->flush();

        $jsonContent=$normalizer->normalize($menu,'json',['groups' => "menu"]);
        return new Response("Menu mise à jour avec succés" . json_encode($jsonContent));
    }

    #[Route('/DeleteMenujson/{id}', name: 'app_DeleteMenujson')]
    public function DeleteMenuJson(NormalizerInterface $normalizer,Request $request,ManagerRegistry $mg,$id)
    {
        $em = $mg->getManager();
        $menu=$em->getRepository(Menu::class)->find($id);
        $em->remove($menu);
        $em->flush();
        $jsonContent=$normalizer->normalize($menu,'json',['groups' => "menu"]);
        return new Response("Menu supprimé avec succés" . json_encode($jsonContent));
    }
*/
}
