<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculeIMCController extends AbstractController
{
    #[Route('/calcule/i/m/c', name: 'app_calcule_i_m_c')]
    public function index(): Response
    {
        return $this->render('calcule_imc/index.html.twig', [
            'controller_name' => 'CalculeIMCController',
        ]);
    }

   #[Route('/calculeimc', name: 'app_calculeimc')]
   public function calculIMC(Request $request)
    {
        $poids = $request->request->get('poids');
        $taille = $request->request->get('taille');
        
        if ($taille > 0) {
            $imc = $poids / ($taille * $taille);
        } else {
            // handle the case where taille is zero or negative
            $imc = null; // or set to a default value
        }
    
        return $this->render('calcule_imc/index.html.twig', [
            'imc' => $imc,
        ]);
    }
   /**  public function calculIMC(Request $request)
   * {
    *    $form = $this->createForm(CalculeIMCType::class);
     *   $form->handleRequest($request); 
      *  if ($form->isSubmitted() && $form->isValid()) {
       *     $data = $form->getData();
        *    $poids = $data['poids'];
         *   $taille = $data['taille'];
          *  $imc = $poids / ($taille * $taille);
           * return $this->render('calcule_imc/index.html.twig', [
            *    'imc' => $imc,
            *]);
        *}
    *}*/
}
