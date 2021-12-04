<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Handler\CommentHandler;
use App\Handler\PostHandler;
use App\Security\Voter\PostVoter;
use App\Uploader\UploaderInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController.
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
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
            'range' => $range,
        ]);
    }

    /**
     * @Route("/article-{id}", name="blog_read")
     */
    public function read(
        Post $post,
        Request $request,
        CommentHandler $commentHandler
    ): Response {
        $comment = new Comment();
        $comment->setPost($post);

        $options = [
            'validation_groups' => $this->isGranted('ROLE_USER') ? 'Default' : ['Default', 'anonymous'],
        ];

        if ($commentHandler->handle($request, $comment, $options)) {
            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/read.html.twig', [
            'post' => $post,
            'form' => $commentHandler->createView(),
        ]);
    }

    /**
     * @Route("/publier-article", name="blog_create")
     */
    public function create(
        Request $request,
        PostHandler $postHandler
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $post = new Post();
        $post->setUser($this->getUser());

        $options = [
            'validation_groups' => ['Default', 'create'],
        ];
        if ($postHandler->handle($request, $post, $options)) {
            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'form' => $postHandler->createView(),
        ]);
    }

    /**
     * @Route("/modifier-article/{id}", name="blog_update")
     */
    public function update(
        Request $request,
        Post $post,
        UploaderInterface $uploader,
        PostHandler $postHandler
    ): Response {
        $this->denyAccessUnlessGranted(PostVoter::EDIT, $post);

        if ($postHandler->handle($request, $post)) {
            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/update.html.twig', ['form' => $postHandler->createView()]);
    }
}
