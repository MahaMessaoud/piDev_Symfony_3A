<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PostJSONController extends AbstractController
{
    #[Route('/decollete', name: 'decollete', methods: ['GET'])]
    public function ddddddd( EntityManagerInterface $em, NormalizerInterface $normalizer,Request $request): Response
    {
        $post = $em->getRepository(Comment::class)->find($request->get('id'));
        $em->remove($post);
        $em->flush();
        $matNormalize = $normalizer->normalize($post, 'json', ['groups' => "comments"]);

        return new Response("Comment deleted successefully".json_encode($matNormalize));
    }
    #[Route('/testC',name:'app_testCC')]
    public function indexjsoooonn(PostRepository $postRepository,NormalizerInterface $serializer,Request $request)
    {
        //get all posts
        $post=$postRepository->find($request->get('idp'));
        $comments= $post->getComments();
        $postserialize=$serializer->normalize($comments , 'json', ['groups' => "comments"]);
        $query= json_encode($postserialize);
        return new Response($query);
    }
    #[Route('/deleteeeJ',name:'app_deleteeej')]
    public function deleteJ( EntityManagerInterface $em, NormalizerInterface $normalizer,Request $request): Response
    {
        $post = $em->getRepository(Comment::class)->find($request->get('id'));
        $em->remove($post);
        $em->flush();
        $matNormalize = $normalizer->normalize($post, 'json', ['groups' => "comments"]);

        return new Response("Comment deleted successefully".json_encode($matNormalize));
    }
    //Json functions


    #[Route('/new/json', name: 'app_comment_new_json', methods: ['GET', 'POST'])]
    public function Jnew(Request $request, EntityManagerInterface $em,PostRepository $pr, NormalizerInterface $normalizer,UserRepository $ur): Response
    {


        $comment = new Comment();
        $userr = $ur->findOneBy(88);
        $comment->setUser($userr);
        $description = $this->filterwords($request->get('contenu'));
        $comment->setDescription($description);
        $comment->setDateCom(new \DateTime('now'));

        $comment->setPost($pr->findOneBy(['id' => $request->get('post')]));
        $em->persist($comment);
        $em->flush();

        $JsonContent = $normalizer->normalize($comment , 'json', ['groups' => "comments"]);
        return new Response(json_encode($JsonContent));

    }
#[Route('/edittJ',name:'app_edittj')]
public function Jeditttt(Request $request, EntityManagerInterface $em, NormalizerInterface $normalizer): Response
{

    $comment = $em->getRepository(Comment::class)->find($request->get('id'));
    $comment->setId($comment->getId());
    $description = $this->filterwords($request->get('content'));
    $comment->setDescription($description);

    $comment->setDateCom($comment->getDateCom());
    $comment->setPost($comment->getPost());
    $em->persist($comment);
    $em->flush();

    $JsonContent = $normalizer->normalize($comment , 'json', ['groups' => "comments"]);
    return new Response("Comment updated successfully".json_encode($JsonContent));

}

    public function filterwords($text)
    {
        $filterWords = array('word1', 'word2', 'word3');
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
