<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\ImageType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\FormView;
#[Route('/post')]
class PostController extends AbstractController
{

    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository,PaginatorInterface  $paginator,Request $request): Response
    {
  $postss=$postRepository->findAll();
  $postss=$paginator->paginate(
      $postss,
      $request->query->getInt('page',1),
      limit: 2
  );
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
            'postss' => $postss,
        ]);
    }
//Json functions

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostRepository $postRepository,SluggerInterface $slugger): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rr = $this->filterwords($post->getTitle());

            $post->setTitle($rr);
            $rr = $this->filterwords($post->getDetails());

            $post->setDetails($rr);
            $post->setDatePost(new \DateTime('now'));
            $post->setRate(0);
            $post->setUser($this->getUser());
           /* $brochureFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setImage($newFilename);
            }*/
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
    #[Route('/photo/{id}', name: 'app_photo', methods: ['GET', 'POST'])]
    public function photo(Request $request, PostRepository $postRepository,Post $post): Response
    {
        $form = $this->createForm(ImageType::class,$post);
        $form->handleRequest($request);
        $filesystem = new Filesystem();
        if($form->isSubmitted())
        {
            $uploadfile = $form->get('image')->getData();
            $formData = $uploadfile->getPathname();
            $sourcePath=strval($formData);
            $destination = 'uploads/posts/photo'.strval($post->getId()).'.png';
            $post->setImage($destination);
            $filesystem->copy($sourcePath,$destination);
            $postRepository->save($post,true);
            return $this->redirectToRoute('app_post_show',['id'=>$post->getId()],Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/image.html.twig', [
        'form' => $form,
        ]);
    }

   /* #[Route('/my-post', name: 'my_post', methods: ['GET'])]
    public function myPostAction()
    {
        $url = $this->generateUrl('my_post', [], true); // Generate the URL for the current page
        $facebookUrl = 'https://www.facebook.com/dialog/share?app_id=100020122154470&href=' . urlencode($url) . '&quote=Check out this awesome post!'; // Replace app_id with your Facebook app ID
        dump($facebookUrl);
        return $this->render('post/my_post.html.twig', [
            'facebookUrl' => $facebookUrl,
        ]);
    }*/

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $img=$post->getImage();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rr = $this->filterwords($post->getTitle());

            $post->setTitle($rr);
            $rr = $this->filterwords($post->getDetails());

            $post->setDetails($rr);
            $post->setImage($img);
            $post->setRate($post->getRate());
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_post_show',['id'=>$post->getId()],Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, PostRepository $postRepository,CommentRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $comments=$post->getComments();
            foreach($comments as $comment){
                $commentRepository->remove($comment, true);
            }

            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
    public function filterwords($text)
    {
        $filteerWords = array('fokaleya', 'bhim', 'msatek', 'fuck', 'slut', 'fucku');
        $filterCount = count($filteerWords);
        $str = "";
        $data = preg_split('/\s+/', $text);
        foreach ($data as $s) {
            $g = false;
            foreach ($filteerWords as $lib) {
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
