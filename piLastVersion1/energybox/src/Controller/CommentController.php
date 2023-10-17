<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/comment')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new($id,Request $request, PostRepository $rep ,CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $comment->setPost($rep->find($id));
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rr = $this->filterwords($comment->getDescription());

            $comment->setUser(
                $this->getUser()
            );
            $comment->setDescription($rr);
            $comment->setDateCom(new \DateTime('now'));
            $commentRepository->save($comment, true);


            return $this->redirectToRoute('app_post_show', ['id'=>$id]);        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_show', methods: ['GET'])]
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rr = $this->filterwords($comment->getDescription());

            $comment->setDescription($rr);
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('app_post_show', ['id'=>$comment->getPost()->getId()]);;        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $id =$comment->getPost()->getId();

            $commentRepository->remove($comment, true);
            return $this->redirectToRoute('app_post_show', ['id'=>$id]);;
        }
        $id =$comment->getPost()->getId();

        return $this->redirectToRoute('app_post_show', ['id'=>$id]);;    }

public function filterwords($text)
{
    $filterWords = array('fokaleya', 'bhim', 'msatek', 'fuck', 'slut', 'fucku');
    $filterCount = count($filterWords);
    $str = "";
    $data = preg_split('/\s+/', $text);
    foreach ($data as $s) {
        $g = false;
        foreach ($filterWords as $lib) {
            if ($s == $lib) {
                $t = "";
                for ($i = 0; $i < strlen($s); $i++) $t .= "*";
                $str .= $t . " ";
                $g = true;
                break;
            }
        }
        if (!$g)
            $str .= $s . " ";
    }
    return $str;
}
}