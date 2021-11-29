<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Security\Voter\PostVoter;
use App\Uploader\UploaderInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(Request $request): Response
    {
        $limit = $request->get('limit', 10) * 1;
        $page = $request->get('page', 10) * 1;

//        $total = $this->getDoctrine()->getRepository(Post::class)->count([]);
        /** @var Paginator<Post> $posts */
        $posts = $this->getDoctrine()->getRepository(Post::class)->getPaginatedPosts(
            $page,
            $limit
        );

        $pages = ceil($posts->count() / 10);

        $range = range(
            max($page - 3, 1),
            min($page + 3, $pages)
        );

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'pages' => $pages,
            'page' => $page,
            'limit' => $limit,
            'range' => $range
        ]);
    }

    /**
     * @Route("/article-{id}", name="blog_read")
     * @param Post $post
     * @param Request $request
     * @return Response
     */
    public function read(Post $post, Request $request): Response
    {
        $comment = new Comment();
        $comment->setPost($post);

        if ($this->isGranted('ROLE_USER')) {
            $comment->setUser($this->getUser());
        }

        $form = $this->createForm(CommentType::class, $comment, [
            "validation_groups" => $this->isGranted('ROLE_USER') ? "Default" : ["Default", "anonymous"]
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/read.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/publier-article", name="blog_create")
     * @param Request $request
     * @param UploaderInterface $uploader
     * @return Response
     */
    public function create(
        Request $request,
        UploaderInterface $uploader
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $post = new Post();
        $post->setUser($this->getUser());

        $form = $this->createForm(PostType::class, $post, [
            'validation_groups' => ['Default', 'create']
        ])->handleRequest($request);
//        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            $post->setImage($uploader->upload($file));

            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modifier-article/{id}", name="blog_update")
     * @param Request $request
     * @param Post $post
     * @param SluggerInterface $slugger
     * @param string $uploadsRelativeDir
     * @param string $uploadsAbsoluteDir
     * @return Response
     */
    public function update(Request $request,
                           Post    $post,
                           UploaderInterface $uploader
    ): Response {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);

        $form = $this->createForm(PostType::class, $post)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file !== null) {
                $post->setImage($uploader->upload($file));
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }
        return $this->render('blog/update.html.twig', ['form' => $form->createView()]);
    }
}
