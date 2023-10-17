<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChatController extends AbstractController
{
    private $chatbot;
    public function __construct(ClientInterface $chatbot)
    {
        $this->chatbot = $chatbot;
    }

    #[Route('/chat', name: 'app_chat')]
    public function index(Request $request): Response
    {
        //create form

        //declare $response Response
        $response = null;


        $form = $this->createFormBuilder(null)
            ->add('message', TextType::class)
            // ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $response = $this->chatbot->post('/v1/completions', [
                'headers' => [
                    'Content-Type' => 'application/json; charset=UTF-8',
                    'Authorization' => 'Bearer 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',
                ],
                'json' => [
                    'model' => 'text-davinci-002',
                    'prompt' => $form->get('message')->getData(),
                    
                ],
            ]);


            $data = json_decode($response->getBody(), true);

            $message = $data['choices'][0]['text'];
            return $this->render('chat/index.html.twig', [
                'form' => $form->createView(),
                'message' => $message,
            ]);
        }
        return $this->render('chat/index.html.twig', [
            'form' => $form->createView(),
            'message' => $response,
        ]);
    }
}
