<?php


namespace App\Controller;

use App\Entity\Post;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->getAllPosts();
        return $this->render("index.html.twig", [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/article-{id}", name="blog_read")
     * @param Post $post
     * @return Response
     */
    public function read(Post $post): Response
    {
        return $this->render("read.html.twig", [
            'post' => $post
        ]);
    }

 }